<style scoped>

    .gap{
        padding-top: 200px
    }

    #taskInputBox{
        /* text-align: center; */
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0.75rem;

    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0.75rem;
    }

    .row {
        position: relative;
    }

    .hover-btn {
        position: absolute;
        right: 15px;
        display: none;
    }

    .row:hover .hover-btn {
        display: block;
    }

</style>

<template>
    <div class="container">
        <div class="gap"></div>

        <p id="title"><font size="50" >todos</font></p>
        
        <!-- create tasks -->                        
        <input type="text" class="form-control" 
            id="taskInputBox"
            placeholder="What needs to be done?"  
            
            v-model="newTask.name" 
            
            v-on:keyup.enter="createTask"

            autofocus
        >





        <!-- show tasks -->
        <div class="card card-body"
            v-for="task in tasks"
            v-bind:key="task.id"
        >
            <div class="row">
                <div class="col-md-10">
                    <h4 @dblclick="task.editing = 1" v-if="task.editing === 0">{{ task.name }}</h4>
                    <input 
                        v-if="task.editing === 1"
                        v-model="task.name"
                        @blur="editTask(task)"
                        @keyup.enter="editTask(task)"
                    >
                </div>

                <div class="hover-btn">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="alert" @click="deleteTask(task)">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            </div>
        </div>





    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Task Component mounted.')
        },

        data() {
            return {
                tasks: [],

                task: {
                    id:'',
                    name:'',
                    editing:0,
                },

                newTask: {
                    id:'',
                    name:'',
                    editing:0,
                },



                edit: false,
                isDisabled: true,

            }
        },

        created() {
            this.getAllTasks();
        },

        methods: {
            
            getAllTasks() {
                    
                    fetch('api/tasks')
                        .then(
                            res => res.json()
                        )
                        .then(
                            res => {
                                this.tasks = res.data;
                                // console.log(res.data);
                                
                            }
                        )
                        .catch( err =>{
                            console.log(err);
                            
                        });
            },

            createTask(){

                if (this.edit == false) 
                {
                    // alert(this.task.name);
                    // console.log(JSON.stringify(this.task));
                    
                    fetch('api/task',{
                        method: 'post',
                        
                        body: JSON.stringify(this.newTask),
                        
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then( res=> res.json())
                    .then( res=> {

                        this.newTask.id = '';
                        this.newTask.name = '';

                        this.getAllTasks();
                    })
                    .catch( 
                        err => console.log(err) 
                    );                

                }

            },

            editTask(task){

                this.task.id = task.id;
                this.task.name = task.name;
                this.task.editing = 1;
                this.edit = true;
                
                // console.log(this.task.name);
                    //api call to update
                fetch( 'api/task', {
                    method: 'put',
                
                    body: JSON.stringify(this.task),

                    headers:{
                        'content-type': 'application/json'
                    }
                })
                .then( res => res.json())
                .then( res => {
                    // console.log(res);

                    this.task.id = '';
                    this.task.name = '';
                    this.task.editing = 0;
                    console.log('Task updated'+ 1);

                    this.getAllTasks();
                })
                .catch( err => console.log(err) ); 


            },

            deleteTask(task) {
                fetch(  `api/task/${task.id}`,{
                    method: 'delete'
                })
                .then( res => res.json())
                .then( res => {
                    this.getAllTasks();
                })
                .catch( err => console.log(err) );                
            },
        },

    }
</script>
