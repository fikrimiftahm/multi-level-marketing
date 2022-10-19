<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ChikaExport implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    protected $count;
    public $date_param;

    public function __construct($date_param)
    {
        $this->date_param = $date_param;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (strpos($this->date_param, 'last month') !== false) {
            $date = new \Carbon\Carbon($this->date_param);
            $date = $date->format('Y-m');
        } else if (strpos($this->date_param, 'yesterday') !== false) {
            $date = new \Carbon\Carbon($this->date_param);
            $date = $date->format('Y-m-d');
        } else {
            $date = new \Carbon\Carbon($this->date_param);
            $date = $date->format('Y-m-d');
        }

        $sqlraw = "
            WITH TDR as (
                SELECT DATE_FORMAT(a.created_date, '%Y%m%d') AS `period`,a.created_date, a.msisdn_plain, a.package_code, a.trans_id, a.client_trans_id, a.developer_app_name,
                ROW_NUMBER() OVER (PARTITION BY a.trans_id, a.client_trans_id ORDER BY a.created_date) as 'rank_id'
                FROM apimanager.trans_data_reward a
                WHERE a.developer_app_name = 'ChikaDataCDOProdApp'
                AND a.created_date LIKE '" . $date . "%'
                ORDER BY a.created_date ASC
            ),
            TDRD as (
                SELECT b.trans_id, b.client_trans_id,b.dr_status, b.dr_date, b.dr_status_desc,
                ROW_NUMBER() OVER (PARTITION BY b.trans_id, b.client_trans_id ORDER BY b.dr_date) as 'rank_id'
                FROM apimanager.trans_data_reward_dr b
                WHERE b.developer_app_name = 'ChikaDataCDOProdApp'
                AND (
                        (	b.dr_date >= '" . $date . "')
                        OR b.dr_date IS NULL
                    )
                AND b.dr_status = 0
                ORDER BY b.dr_date ASC
            ),
            MP as (
                SELECT c.`group` , c.price
                FROM apimanager.master_pricing c
                WHERE c.developer_app_name = 'ChikaDataCDOProdApp'
            ),
            MW as (
                SELECT @balance := g.balance, g.developer_app_name
                FROM apimanager.master_wallet g
                WHERE g.wallet_name = 'CHIKA_CDO_REPORT_PROD'
            ), trans as (
                SELECT DATE_FORMAT(d.created_date, '%Y%m%d') AS `period`,
                d.created_date, 
                d.msisdn_plain, 
                d.package_code, 
                d.trans_id, 
                d.client_trans_id, 
                e.dr_date, 
                e.dr_status_desc,
                f.price
                FROM TDR d
                JOIN TDRD e
                ON d.trans_id = e.trans_id
                JOIN MP f
                ON d.package_code = f.`group`
                JOIN MW i
                ON d.developer_app_name = i.developer_app_name
                WHERE 1 = 1 
                AND d.rank_id = 1
                AND e.rank_id = 1
                ORDER BY d.created_date ASC
            ), result as (
                SELECT `period`,
                created_date, 
                msisdn_plain, 
                package_code, 
                trans_id, 
                client_trans_id, 
                dr_date, 
                dr_status_desc,
                @price := price as 'price',
                @balance as 'begining_balance',
                @balance := @balance - @price as 'end_balance'
                FROM trans
            )
            select *
            from result 
            order by end_balance desc;
        ";

        $data = DB::connection('mysql')->select(DB::connection('mysql')->raw($sqlraw));
        $collection = collect($data);
        $balance = $collection->last()->end_balance;

        $sqlUpdate = "
            UPDATE apimanager.master_wallet mw
            SET mw.balance = " . $balance . " WHERE mw.wallet_name = 'CHIKA_CDO_REPORT_PROD';
        ";

        DB::update(DB::raw($sqlUpdate));

        $this->count = 1 + count($collection);

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Period',
            'Created Date ',
            'MSISDN',
            'Package Code',
            'Trans ID',
            'Client Trans ID',
            'DR Date',
            'DR Status Desc',
            'Price',
            'Begining Balance',
            'End Balance',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => '#,##0',
            'J' => '#,##0',
            'K' => '#,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:K' . $this->count;
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $styleArray = [
                    'font' => [
                        'bold' => false,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                    'borders' => [
                        'allborders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A2:K' . $this->count)->applyFromArray($styleArray);

                $styleAAArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A1:K' . $this->count)->applyFromArray($styleAAArray);

                $event->sheet->getDelegate()->setAutoFilter('A1:K' . $this->count);
            },
        ];
    }
}
