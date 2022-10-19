<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class TestImport implements ToCollection
{
    private $arrInsert = [];
    private $arrUpdate = [];
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', '0');
        $a = [];
        $all = [];
        $sips = [];
        $phones =  DB::table('sip_trunk')->get();
        foreach($phones as $item){
            $all[$item->name] = $item->name;
            if($item->active){
                $sips[] = $item->name;
            }
        }

        foreach($collection as $item){
//            $a = DB::table('sip_trunk')->where('name', $item[0])->first();
            if(isset($all[$item[0]])){
                $this->arrUpdate[] = $item[0];
                $key = array_search($item[0], $sips);
                if($key !== false){
                    unset($sips[$key]);
                }
            }
            else{
                $this->arrInsert[] = [
                    'name'=> $item[0],
                    'active'=> 1,
                    'who_created'=> 'tuandv',
                    'who_updated'=> 'tuandv'
                ];
            }
        }

        DB::beginTransaction();
        try{
            if(count($this->arrInsert)){
                $chunks = array_chunk($this->arrInsert, 2000);
                foreach($chunks as $chunk){
                    DB::table('sip_trunk')->insert($chunk);
                }

            }


            $chunkUpdates = array_chunk($this->arrUpdate, 1000);
            foreach($chunkUpdates as $chunk){
                DB::table('sip_trunk')
                    ->whereIn('name', $chunk)
                    ->update([
                        'active'=> 1,
                        "who_updated"=> 'linh'
                    ]);
            }
            $chunksNotActive = array_chunk($sips, 1000);
            foreach($chunksNotActive as $chunk){
                DB::table('sip_trunk')
                    ->whereIn('name', $chunk)
                    ->update([
                        'active'=> 0,
                        "who_updated"=> 'Canh'
                    ]);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
        }

    }

}
