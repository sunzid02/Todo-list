<style scoped>

    .gap{
        padding-top: 200px
    }

    #title{
        text-align: center;
    }

</style>

<template>
    <div class="container">
        <div class="gap"></div>

        <p id="title"><font size="50" >todos</font></p>
        
        <!-- create tasks -->            
        <div class="form-group">
            
            <input type="text" class="form-control" 
                placeholder="What needs to be done?"  
                
                v-model="task.name" 
                
                v-on:keyup.enter="createTask"

                autofocus
            >

        </div>


        <!-- show tasks -->
        <div class="card card-body"
            v-for="task in tasks"
            v-bind:key="task.id"
        >
            <h4>  {{ task.name }} </h4>
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
                    name:''
                },

                edit: false,

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
                                console.log(res.data);
                                
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
                    console.log(JSON.stringify(this.task));
                    
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
        },

    }
</script>
