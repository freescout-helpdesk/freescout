<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SLASetting;
use App\Conversation;
use Dompdf\Dompdf;
use Dompdf\Options;
use \PDF;
class AutoReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'canidesk:auto-reporting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command is used to send email automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

 

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // $settings=SLASetting::orderBy('id', 'desc')->first();
        // $email=explode(',', $settings->to_email);
        // $this->info($settings);
        $tickets = Conversation::with('user', 'conversationCustomField.custom_field', 'conversationCategory','conversationPriority')->get();
        $dompdf = new Dompdf();
        // (Optional) Set Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Set the default font
        // You can customize other options if needed

        $dompdf->setOptions($options);

        // Load the Blade view with the table data
        $html = view('sla.report-email', compact('tickets'));
        // Load the HTML content
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to the browser
    $dompdf->save('report.pdf');

        $data = array('name'=>"Example");
        // $mail = Mail::send('mail', $data, function($message) {
        // $message->to($settings->to_email, 'Tutorials Point')->subject
        //     ('hello i am rajesh rathod')->attach('/home/rathod/Downloads/report.pdf');
        // $message->from('hello@example.com','Example');
        
        // });
    }
}
