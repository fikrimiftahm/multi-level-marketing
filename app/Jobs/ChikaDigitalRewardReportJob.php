<?php

namespace App\Jobs;

use App\Exports\ChikaExport;
use App\Mail\ReportMail;
use App\Models\MasterUser;
use App\Repository\MailSenderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ChikaDigitalRewardReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date = null)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailSenderRepositoryInterface $mailSenderRepositoryInterface)
    {
        if (isset($this->date)) {
            $filename = "ChikaUsage_" . (new Carbon($this->date))->format('Ymd') . ".xlsx";
            Excel::store(new ChikaExport($this->date), $filename, 'export');
        } else {
            $filename = "ChikaUsage_" . (new Carbon('yesterday'))->format('Ymd') . ".xlsx";
            Excel::store(new ChikaExport('yesterday'), $filename, 'export');
        }

        $reportingId = 12;
        $users = MasterUser::where('status', 0)->where('reporting_id', $reportingId)->get();
        // $usersTo = $users->where('group', 'to');
        $usersTo = ["nindarumind@gmail.com", "fadlan.fasa@gmail.com", "fadlan.fasa@yahoo.co.kr", "nuriah.hasanah95@gmail.com", "feri.ferdianto@ioh.co.id", "muhammad.badruzzaman@ioh.co.id"];
        $usersCc = $users->where('group', 'cc');
        $usersBcc = $users->where('group', 'bcc');
        // $usersTo = ['fikrimiftahm@gmail.com'];
        // $usersCc = ['fikrimiftahm@gmail.com'];
        // $usersBcc = ['fikrimiftahm@gmail.com'];

        $file = public_path('storage/export/' . $filename);

        $mailBody = [
            'title' => 'Daily Report - Paket Gaspol Online PT Chika Mulya Multimedia'
        ];

        $mail = new ReportMail(
            'Daily Report - Paket Gaspol Online PT Chika Mulya Multimedia',
            'email.report',
            $mailBody,
            [$file]
        );

        $mailSenderRepositoryInterface->sendMail($mail, $usersTo, $usersCc, $usersBcc);

        unlink($file);
    }
}
