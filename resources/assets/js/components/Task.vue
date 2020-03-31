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


  .completed {
    text-decoration: line-through;
    color: grey;
  }





.visible {
  visibility: visible;
}
.invisible {
  visibility: hidden;
}

  /* checkbox starts*/

  /* checkbox ends*/

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





        <!-- show all tasks -->
        <div id="allDiv">
            <div class="card card-body"
                v-for="task in tasks"
                v-bind:key="task.id"
            >
                <div class="row">
                    <!-- complete task check -->
                    <div class="col-md-1">             
                        <input type="checkbox" v-model="task.completed" @click="completeTask(task, totalNumberOfTask)" >
                    </div>

                    <div class="col-md-10">
                        <h4 @dblclick="task.editing = 1" v-if="task.editing === 0" :class="{ completed : task.completed }">
                            {{ task.name }}
                        </h4>
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


        <!-- show active tasks -->
        <div  id="activeDiv">
             <div class="card card-body"
                v-for="task in activeTasks"
                v-bind:key="task.id"
            >
                <div class="row">
                    <!-- complete task check -->
                    <div class="col-md-1">             
                        <input type="checkbox" v-model="task.completed" @click="completeTaskFromActiveTab(task, totalNumberOfTask)" >
                    </div>

                    <div class="col-md-10">
                        <h4 @dblclick="task.editing = 1" v-if="task.editing === 0" :class="{ completed : task.completed }">
                            {{ task.name }}
                        </h4>
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






        <!-- show completed tasks -->
        <div  id="completedDiv">
            <div class="card card-body"
                v-for="ct in completedTasks"
                v-bind:key="ct.id"
            >
                <div class="row">
                    <div class="col-md-12">
                        <h4 style="text-decoration: line-through; color: grey">{{ ct.name }}</h4>

                    </div>
                </div>

            </div>
        </div>



        <!-- footer -->
        <hr>
        <div class="row">
            <div class="col-md-4">
                <h6>
                    {{ totalNumberOfTask }} Item left
                </h6>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn btn-primary" @click="allTabVisibility('allDiv')"> All </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" @click="activeTabVisibility('activeDiv')"> Active </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary"  @click="completedTabVisibility('completedDiv')"> Completed </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-info" @click="clearCompleteTask()"> Clear Completed </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap"></div>





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
                completedTasks: [],
                activeTasks: [],

                task: {
                    id:'',
                    name:'',
                    editing:0,
                    completed: 0,
                },

                newTask: {
                    id:'',
                    name:'',
                    editing:0,
                    completed: 0,

                },

                activeTask: {
                    id:'',
                    name:'',
                    editing:0,
                    completed: 0,

                },



                edit: false,
                isDisabled: true,
                totalNumberOfTask: 0,


            }
        },

        created() {
            this.getAllTasks();
            // this.formVisibility();
        },

        methods: {
            
            getAllTasks() {
                    
                    fetch('api/tasks')
                        .then(
                            res => res.json()
                        )
                        .then(
                            res => {
                                this.tasks = [];
                                this.completedTasks = [];
                                this.activeTasks = [];
                                
                                this.tasks = res.data;


                                ////completed task list
                                this.tasks.forEach( task => {
                                    
                                    if (task.completed == 1) 
                                    {
                                        var completedTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 1

                                        };

                                        this.completedTasks.push(completedTaskObj);                                                                                
                                    }
                                });


                                ////active task list
                                this.tasks.forEach( task => {
                                    
                                    if ( task.completed == 0 ) 
                                    {
                                        var activeTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 0

                                        };

                                        this.activeTasks.push(activeTaskObj);                                                                                
                                    }
                                });                                

                                this.totalNumberOfTask = this.tasks.length - this.completedTasks.length;


                                document.getElementById("allDiv").style.display="block";
                                document.getElementById("completedDiv").style.display="none";
                                document.getElementById("activeDiv").style.display="none";


                                // alert(res.data.length);
                                
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
                    this.newTask.id = '';
                    this.newTask.name = '';
                    this.task.editing = 0;
                    this.newTask.editing = 0;
                    this.edit= false;
                    // console.log('Task updated'+ 1);

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

            clearCompleteTask(task) {
                fetch(  `api/clear-complete-task`,{
                    method: 'delete'
                })
                .then( res => res.json())
                .then( res => {
                    // this.getAllTasks();
                    // this.completedTabVisibility('completedDiv');

                    // this.getAllTasks();
                    fetch('api/tasks')
                        .then(
                            res => res.json()
                        )
                        .then(
                            res => {
                                this.tasks = [];
                                this.completedTasks = [];
                                this.activeTasks = [];
                                
                                this.tasks = res.data;


                                ////completed task list
                                this.tasks.forEach( task => {
                                    
                                    if (task.completed == 1) 
                                    {
                                        var completedTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 1

                                        };

                                        this.completedTasks.push(completedTaskObj);                                                                                
                                    }
                                });


                                ////active task list
                                this.tasks.forEach( task => {
                                    
                                    if ( task.completed == 0 ) 
                                    {
                                        var activeTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 0

                                        };

                                        this.activeTasks.push(activeTaskObj);                                                                                
                                    }
                                });                                

                                this.totalNumberOfTask = this.tasks.length - this.completedTasks.length;


                                document.getElementById("allDiv").style.display="none";
                                document.getElementById("activeDiv").style.display="none";
                                document.getElementById("completedDiv").style.display="block";


                                // alert(res.data.length);
                                
                            }
                        )
                        .catch( err =>{
                            console.log(err);
                            
                        });
                })
                .catch( err => console.log(err) );                
            },

            completeTask(task, totalNumberOfTask){
                // document.getElementById("completedDiv").style.display="none";

                this.task.completed = 1;
                this.task.edit = false;
                this.edit = false;

                this.task.id = task.id;
                this.task.name = task.name;
                this.task.editing = 0;
                this.task.completed = 1;




                //api call to update
                fetch(`api/complete-task/${task.id}`,{
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
                    this.task.completed = 0;

                    this.newTask.id = '';
                    this.newTask.name = '';
                    this.newTask.editing = 0;
                    this.newTask.completed = 0;
                    // console.log('Task updated'+ 1);

                    this.getAllTasks();
                })
                .catch( err => console.log(err) ); 
                // console.log(this.completedTasks);
            },

            completeTaskFromActiveTab(task, totalNumberOfTask){
                // document.getElementById("completedDiv").style.display="none";

                this.task.completed = 1;
                this.task.edit = false;
                this.edit = false;

                this.task.id = task.id;
                this.task.name = task.name;
                this.task.editing = 0;
                this.task.completed = 1;




                //api call to update
                fetch(`api/complete-task/${task.id}`,{
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
                    this.task.completed = 0;

                    this.newTask.id = '';
                    this.newTask.name = '';
                    this.newTask.editing = 0;
                    this.newTask.completed = 0;
                    // console.log('Task updated'+ 1);

                    // this.getAllTasks();
                    fetch('api/tasks')
                        .then(
                            res => res.json()
                        )
                        .then(
                            res => {
                                this.tasks = [];
                                this.completedTasks = [];
                                this.activeTasks = [];
                                
                                this.tasks = res.data;


                                ////completed task list
                                this.tasks.forEach( task => {
                                    
                                    if (task.completed == 1) 
                                    {
                                        var completedTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 1

                                        };

                                        this.completedTasks.push(completedTaskObj);                                                                                
                                    }
                                });


                                ////active task list
                                this.tasks.forEach( task => {
                                    
                                    if ( task.completed == 0 ) 
                                    {
                                        var activeTaskObj = {
                                            id : task.id,
                                            name : task.name,
                                            editing : 0,
                                            completed : 0

                                        };

                                        this.activeTasks.push(activeTaskObj);                                                                                
                                    }
                                });                                

                                this.totalNumberOfTask = this.tasks.length - this.completedTasks.length;


                                document.getElementById("allDiv").style.display="none";
                                document.getElementById("completedDiv").style.display="none";
                                document.getElementById("activeDiv").style.display="block";


                                // alert(res.data.length);
                                
                            }
                        )
                        .catch( err =>{
                            console.log(err);
                            
                        });













                })
                .catch( err => console.log(err) ); 
                // console.log(this.completedTasks);
            },


            allTabVisibility(div){

                document.getElementById("allDiv").style.display="none";
                document.getElementById("completedDiv").style.display="none";
                document.getElementById("activeDiv").style.display="none";

                document.getElementById(div).style.display="block";                
                console.log("all btn clicked");
            },

            completedTabVisibility(div){

                document.getElementById("allDiv").style.display="none";
                document.getElementById("activeDiv").style.display="none";
                document.getElementById("completedDiv").style.display="none";

                document.getElementById(div).style.display="block";  

                console.log("completed btn clicked");

            },

            activeTabVisibility(div){
                document.getElementById("allDiv").style.display="none";
                document.getElementById("completedDiv").style.display="none";
                document.getElementById("activeDiv").style.display="none";

                document.getElementById(div).style.display="block";  

                console.log("active btn clicked");

            },


            formVisibility(){
                document.getElementById("allDiv").style.display="block";
                document.getElementById("activeDiv").style.display="none";
                document.getElementById("completedDiv").style.display="none";
            },
        },

    }
</script>
