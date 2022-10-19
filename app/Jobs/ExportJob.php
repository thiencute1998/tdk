<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $items;
    private $times;

    /**
     * Create a new job instance.
     *
     * @param $items
     * @param $times
     */
    public function __construct($items, $times)
    {
        //
        $this->items = $items;
        $this->times = $times;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load(storage_path() . "/app/public/test.xlsx");

        $index = 2;
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        foreach($this->items as $value){
            $sheet->setCellValue('B'. $index, $value->name)
                ->setCellValue('C'. $index, $value->phone)
                ->setCellValue('D'. $index, $value->date)
                ->setCellValue('E'. $index, $value->gender)
                ->setCellValue('F'. $index, $value->company);
            $index++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path() . "/app/public/test" . $this->times . ".xlsx");
    }
}
