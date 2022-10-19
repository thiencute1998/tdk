<template>
    <div>
        <div>12345</div>
        <button @click="exportExcel()">Export</button>
        <div>
            <button @click="downloadExcel">Download</button>
        </div>
        <form @submit="importFile" enctype="multipart/form-data">
            <div>
                <input type="file" name="filename" v-on:change="onFileChange">
            </div>
            <input type="submit" class="btn btn-primary" value="Upload">

        </form>

        <div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "App",
    data(){
        return {
            start: 0,
            times: 0,
            countFile: 0,
            files: [],
            filesDownload : [
                'test1.xlsx',
                'test2.xlsx',
                'test3.xlsx',
                'test4.xlsx'
            ],
            isDownload: false,
            filename: '',
            file: '',
        }
    },
    methods: {
        async exportExcel(){
            let vm = this;
            let res = await axios.post('api/v1/export?XDEBUG_SESSION_START=PHPSTORM',
                {
                    offset: vm.start,
                    times: vm.times
                });
            let response = res.data;
            vm.start += 100000;
            if(vm.start < response.total){
                vm.times++;
                vm.files.push(response.file);
                await vm.exportExcel();
            } else {
                // console.log('et')
                let data = axios({
                    url: 'api/v1/download?XDEBUG_SESSION_START=PHPSTORM',
                    method: 'POST',
                    data: {file: vm.files},
                    responseType: 'blob', // important
                }).then(async (response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'abc' + vm.countFile + ".xlsx");
                    document.body.appendChild(link);
                    link.click();
                    vm.countFile++;
                    vm.isDownload = true;
                    if(vm.countFile < vm.files.length){
                        await vm.exportExcel();
                    }
                });

            }

        },
        downloadExcel(){
            let vm = this;
            let data = axios({
                url: 'api/v1/download?XDEBUG_SESSION_START=PHPSTORM',
                method: 'POST',
                data: {file: vm.filesDownload},
                responseType: 'blob', // important
            }).then(async (response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'abc.xlsx');
                document.body.appendChild(link);
                link.click();
                // if(vm.countFile < vm.files.length){
                //     await vm.exportExcel();
                // }
            });
        },
        onFileChange(e) {
            this.filename = "Selected File: " + e.target.files[0].name;
            this.file = e.target.files[0];
        },
        importFile(e) {
            e.preventDefault();
            let form = new FormData();
            form.append('fileName', this.file);

            let vm = this;
            axios({
                url: 'api/v1/import?XDEBUG_SESSION_START=PHPSTORM',
                method: 'POST',
                data: form
            }).then(async (response) => {
                console.log(response)
            });
        }
    }
}
</script>

<style scoped>

</style>
