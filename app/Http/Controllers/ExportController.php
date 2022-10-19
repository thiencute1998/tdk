<?php

namespace App\Http\Controllers;

use App\Imports\TestImport;
use App\Jobs\ExportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use Box\Spout\Reader\ReaderFactory;
//use Box\Spout\Common\Type;
//use Box\Spout\Writer\WriterFactory;


class ExportController extends Controller
{
    //

//    public function export(Request $request){
////        $data = DB::table('person')->paginate(100);
////        $total = DB::table('person')
////            ->where('id', '<', '5000')
////            ->count();
//        ini_set('max_execution_time', '0'); // for infinite time of execution
//
//        $total = 500001;
//        $limit = 100000;
//
//        $item = DB::table('person')
//            ->where('id', '<', $total)
//            ->offset((int)$request->post('offset'))
//            ->limit($limit)
//            ->get();
//
//        $data = [
//            'total'=> $total,
////            'data'=> $item
//        ];
//        $times = $request->post('times');
//        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
//        if($times == 0){
//            $spreadsheet = $reader->load(storage_path() . "/app/public/test.xlsx");
//        }
//        else{
//            $spreadsheet = $reader->load(storage_path() . "/app/public/test0.xlsx");
//        }
////        $spreadsheet->getActiveSheet(0)->setCellValue('A2', 1);
//        $spreadsheet->createSheet();
//
//        $index = 2;
//        $sheet = $spreadsheet->setActiveSheetIndex($times);
//        foreach($item as $value){
//            $sheet->setCellValue('B'. $index, $value->name)
//                ->setCellValue('C'. $index, $value->phone)
//                ->setCellValue('D'. $index, $value->date)
//                ->setCellValue('E'. $index, $value->gender)
//                ->setCellValue('F'. $index, $value->company);
//            $index++;
//        }
//        $spreadsheet->getActiveSheet()->setTitle('Test' . $times);
//
////        $spreadsheet->setActiveSheetIndex($times)->setCellValue('A1', 'Tester');
////        $spreadsheet->createSheet();
////        $spreadsheet->setActiveSheetIndex(2)->setCellValue('A2', 'Manual Tester');
////        $spreadsheet->getActiveSheet()->setTitle('Third tab');
//        $writer = new Xlsx($spreadsheet);
//        $writer->save(storage_path() . "/app/public/test0.xlsx");
//        return Response::json($data, 200);
//    }

//    public function export(Request $request){
////        $data = DB::table('person')->paginate(100);
////        $total = DB::table('person')
////            ->where('id', '<', '5000')
////            ->count();
//        ini_set('max_execution_time', '0'); // for infinite time of execution
//        $times = $request->post('times');
//        $total = 500001;
//        $limit = 100000;
//
//        $item = DB::table('person')
//            ->where('id', '<', $total)
//            ->offset((int)$request->post('offset'))
//            ->limit($limit)
//            ->get();
//
//        $data = [
//            'total'=> $total,
//            'file'=> 'test' . $times . '.xlsx'
//        ];
//
//        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
//        $spreadsheet = $reader->load(storage_path() . "/app/public/test.xlsx");
//
//        $index = 2;
//        $sheet = $spreadsheet->setActiveSheetIndex(0);
//        foreach($item as $value){
//            $sheet->setCellValue('B'. $index, $value->name)
//                ->setCellValue('C'. $index, $value->phone)
//                ->setCellValue('D'. $index, $value->date)
//                ->setCellValue('E'. $index, $value->gender)
//                ->setCellValue('F'. $index, $value->company);
//            $index++;
//        }
//        $writer = new Xlsx($spreadsheet);
//        $writer->save(storage_path() . "/app/public/test" . $times . ".xlsx");
//        return Response::json($data, 200);
//    }

//    public function export(Request $request){
////        $data = DB::table('person')->paginate(100);
////        $total = DB::table('person')
////            ->where('id', '<', '5000')
////            ->count();
//        ini_set('max_execution_time', '0'); // for infinite time of execution
//        $times = $request->post('times');
//        $total = 500001;
//        $limit = 100000;
//
//        $item = DB::table('person')
//            ->where('id', '<', $total)
//            ->offset((int)$request->post('offset'))
//            ->limit($limit)
//            ->get();
//
//        $data = [
//            'total'=> $total,
//            'file'=> 'test' . $times . '.xlsx'
//        ];
//
//        ExportJob::dispatch($item, $times);
//        return Response::json($data, 200);
//    }

    public function export (Request $request){
        ini_set('max_execution_time', '0'); // for infinite time of execution
        $times = $request->post('times');
        $total = 500001;
        $limit = 100000;
        $handler = fopen(storage_path() . "/app/public/hihi.csv", 'w');
        fputcsv($handler, array('ID', 'name', 'phone', 'date', 'gender', 'company'));
          DB::table('people')
            ->where('id', '<', $total)
            ->offset((int)$request->post('offset'))
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->chunk(5000, function($value) use (&$handler){
                foreach($value as $i){
                    fputcsv($handler, (array)$i);
                }

            });
//          fclose($handler);

        $data = [
            'total'=> $total,
            'file'=> 'test' . $times . '.xlsx'
        ];

        return Response::json($data, 200);


    }

//    public function download(Request $request){
//        $files = $request->post('file');
//        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
//        $spreadsheet = $reader->load(storage_path() . "/app/public/" . $files[0]);
//        foreach($files as $key=> $file){
//            $spread = $reader->load(storage_path() . "/app/public/" . $file);
//            $clonedWorksheet = clone $spread->getSheetByName('Sheet1');
//            $clonedWorksheet->setTitle('test' . $key);
//            $spreadsheet->addSheet($clonedWorksheet);
//        }
//        $writer = new Xlsx($spreadsheet);
//        $writer->save(storage_path() . "/app/public/test" . $files[0]);
//        return response()->download(storage_path('app/public/' . $files[0]));
//    }



//    public function download(Request $request){
//        $reader = ReaderFactory::create(Type::XLSX);
//        $reader->setFieldDelimiter('|');
//        $reader->setFieldEnclosure('@');
//        $reader->setEndOfLineCharacter("\r");
//        $reader->setEncoding('UTF-16LE');
//        $writer = WriterFactory::create(Type::CSV);
//        $writer->setShouldAddBOM(false);
//    }

    public function import(Request $request) {
//        Excel::import(new TestImport, $request->file('fileName'));
        ini_set('max_execution_time', '0');
        Excel::import(new TestImport, $request->file('fileName'));
        return 1;
    }
}
