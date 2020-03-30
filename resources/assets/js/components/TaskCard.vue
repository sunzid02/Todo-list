<template>
	<div id="todo">
		<div class="row">
	
			<h3 @dblclick="edit = true" v-if="edit === false">{{ task.name }}</h3>
			
			<input 
				v-if="edit === true"
				v-model="task"
				@blur="editTask()"
				@keyup.enter="editTask()"
			>
		
		<button 
			@click="removeTodo"
			type="button"
			class="btn btn-danger btn-sm"
		>Delete</button>
    </div>
  </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        data() {
            return {
                tasks: [],

                task: {
                    id:'',
                    name:''
                },

                edit: false,
                isDisabled: true,

            }
        },        

        methods: {
           props: ['task'],

            editTask(task){
                // this.isDisabled = false;

                // document.getElementById(task.id).attr({
                //     'disabled': this.isDisabled
                // });

                
                console.log(task.name);
                
                console.log(task.id);
            }
        },
    }
</script>
