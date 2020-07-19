<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todolist Sederhana Laravel - Vue</title>

    {{-- boostrap min css --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    {{-- sweetalert  min css--}}
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css" rel="stylesheet">

    <style>
        .todolist-wrapper {
            border: 1px solid #cccccc;
            min-height: auto;
        }
        .table {
            margin-bottom: 0 !important;
        }
    </style>
</head>
<body>
    <div class="container p-4">
        <div id="app">
             {{-- modal tambah todolist --}}
            <div class="modal modal-todolist" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Form Tambah Todolist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" rows="10" v-model="content" id="content" placeholder="Tambah todolist..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="saveTodoList">Save</button>
                    </div>
                </div>
                </div>
            </div>
            {{-- modal edit todolist --}}
            <div class="modal modal-edit-todolist" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Form edit Todolist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" v-model="id_todolist">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" rows="10" v-model="edit_content" id="edit_content"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateTodoList">Save</button>
                    </div>
                </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-success shadow" v-on:click="showModal">Add Todolist</button>
                    </div>
                    <div class="todolist-wrapper shadow">
                        <table class="table table-striped table-bordered">
                            <tbody>

                                <tr v-if="!data_list.length">
                                    <td class="text-center">Data Masih Kosong</td>
                                </tr>
                                <tr v-else="data_list.length" v-for="item in data_list">
                                    <td><span>@{{item.content}}</span> <button type="button" class="btn btn-warning btn-sm text-light float-right ml-2 shadow" @click="editTodoList(item.id)"> Edit</button><button type="button" class="btn btn-danger btn-sm text-light float-right shadow" @click="deleteTodoList(item.id)"> Delete</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>
    {{-- Vue js --}}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    {{-- axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var vue = new Vue({
            el: '#app',
            data: {
                data_list : [],
                content: "",
                id:"",
                edit_content: "",
                id_todolist: ""
            },
            mounted() {
                this.getDataList()
            },
            methods: {
                showModal : function() {
                    $('.modal-todolist').modal('show')
                },
                saveTodoList: function() {
                    let form_data = new FormData()
                    form_data.append('content', this.content)
                    axios.post("{{url('api/todolist/create')}}", form_data)
                    .then(res => {
                        alert(res.data.message)
                        this.content = ""
                        this.getDataList()
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan pada sistem')
                    })
                    .finally(function() {
                        $('.modal-todolist').modal('hide')
                    })
                },
                getDataList: function() {
                    axios.get("{{url('api/todolist/list')}}")
                    .then(res => {
                        this.data_list = res.data
                        // console.log(res.data)
                    })
                    .catch(err => {
                        alert('Terjadi Kesalahan pada sistem')
                    })
                },
                editTodoList: function(id) {
                    let form_data = new FormData()
                    this.id = id
                    form_data.append('id', id)
                    axios.post("{{url('api/todolist/edit')}}", form_data)
                    .then(res => {
                        this.id_todolist = res.data.id
                        this.edit_content = res.data.content
                    })
                    .catch(err => {
                            alert('Terjadi Kesalahan pada sistem')
                    })
                    .finally(() => {
                        $('.modal-edit-todolist').modal('show')
                    })
                },
                updateTodoList: function() {
                    let form_data = new FormData()
                    form_data.append('id', this.id_todolist)
                    form_data.append('content', this.edit_content)
                    axios.post("{{url('api/todolist/update')}}", form_data)
                    .then(res => {
                        alert(res.data.message)
                        this.getDataList()
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan pada sistem')
                    })
                    .finally(() => {
                        $('.modal-edit-todolist').modal('hide')
                    })
                },
                deleteTodoList: function(id) {
                    let form_data = new FormData()
                    this.id = id
                    form_data.append('id', id)
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will delete this todolist",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                        if (result.value) {
                             axios.post("{{url('api/todolist/delete')}}", form_data)
                            .then(res => {
                                Swal.fire(
                                    'Deleted!',
                                    'Your todolist has been deleted.',
                                    'success'
                                )
                                this.getDataList()
                            })
                            .catch(err => {
                                Swal.fire(
                                    'Oopss...',
                                    'Something went wrong',
                                    'error'
                                )
                            })
                        }else {
                            return false
                        }
                    })
                    // axios.post("{{url('api/todolist/delete')}}", form_data)
                    // .then(res => {
                    //     alert(res.data.message)
                    //     this.getDataList()
                    // })
                    // .catch(err => {
                    //     alert('Terjadi kesalahan pada sistem')
                    // })
                }
            }
        })
    </script>
</body>
</html>
