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

</style>

<template>
    <div class="container">
        <div class="gap"></div>

        <p id="title"><font size="50" >todos</font></p>
        
        <!-- create tasks -->                        
        <input type="text" class="form-control" 
            id="taskInputBox"
            placeholder="What needs to be done?"  
            
            v-model="task.name" 
            
            v-on:keyup.enter="createTask"

            autofocus
        >





        <!-- show tasks -->
        <div class="card card-body"
            v-for="task in tasks"
            v-bind:key="task.id"
        >

        
			<h4 @dblclick="task.editing = 1" v-if="task.editing === 0">{{ task.name }}</h4>
			<input 
				v-if="task.editing === 1"
				v-model="task.name"
				@blur="editTask(task)"
				@keyup.enter="editTask(task)"
			>


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
                        
                        body: JSON.stringify(this.task),
                        
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then( res=> res.json())
                    .then( res=> {

                        this.task.id = '';
                        this.task.name = '';

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


            }
        },

    }
</script>
