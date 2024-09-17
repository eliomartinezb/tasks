<script setup>
import {onMounted, ref} from "vue";
import draggable from "vuedraggable";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import Modal from "../components/modal.vue";

const tasks = ref([
    {
        name: "test 1",
        priority: 1,
        project: {
            id: 1,
            name: "quaerat",
        },
    },
    {
        name: "test 2",
        priority: 2,
        project: {
            id: 1,
            name: "quaerat",
        },
    },
    {
        name: "test 3",
        priority: 3,
        project: {
            id: 1,
            name: "quaerat",
        },
    },
]);

const getTasks = () => {
    console.log("getTasks");
    axios
        .get('/api/tasks')
        .then((response) => {
            tasks.value = response.data.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const projects = ref([
    {name: "test 1", priority: 1},
    {name: "test 2", priority: 2},
    {name: "test 3", priority: 3},
]);

const getProjects = () => {
    console.log("getProjects");
    axios.get('/api/projects').then((response) => {
        projects.value = response.data.data
    }).catch((error) => {
        console.log(error);
    })
}

const showDeleteModal = ref(false);
const deleteElement = ref({name: "test 1", priority: 1},);

const openDeleteModal = (element) => {
    showDeleteModal.value = true;
    deleteElement.value = element;
};

const deleteTask = () => {
    axios
        .delete('/api/tasks/' + deleteElement.value.id)
        .then((response) => {
            let index = tasks.value.indexOf(deleteElement.value)
            tasks.value.splice(index, 1);
            deleteElement.value = {name: "test 1", priority: 1};
            getTasks();
            showDeleteModal.value = false
        })
        .catch((error) => {
            console.log(error);
        });
};


const showAddModal = ref(false);
const form = ref({name: "", priority: 0, project_id: 0});

const openAddModal = () => {
    console.log("addTasks");
    showAddModal.value = true;
};

const addTask = () => {
    axios
        .post('/api/tasks', form.value)
        .then(() => {
            form.value.project_id = 0;
            form.value.name = '';
            form.value.priority = 0;
            showAddModal.value = false;
            getTasks();
        })
        .catch((error) => {
            console.log(error);
        });
};

const selectedProjectId = ref(0);
const filterByProject = () => {
    axios
        .get('/api/tasks?project_id=' + selectedProjectId.value)
        .then((response) => {
            tasks.value = response.data.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const clearFilter = () => {
    selectedProjectId.value = 0;
    getTasks();
}

const startDrag = () => {
    return true;
};

const endDrag = () => {
    tasks.value.forEach((task, index) => {
        task.priority = index + 1;
    });

    if (selectedProjectId.value !== 0) {
        return false
    }

    axios
        .put('/api/tasks', {
            tasks: tasks.value,
        })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
    return false;
};

onMounted(() => {
    getTasks();
    getProjects();
});
</script>

<template>
    <modal :show-modal="showAddModal" @close="showAddModal=false">
        <div class="flex flex-col">
            <label class="mb-2">Project</label>
            <select
                class="px-3 py-2 max-w-full focus:ring focus:outline-none dark:placeholder-gray-400' border rounded"
                v-model="form.project_id">
                <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
            </select>
        </div>
        <div class="flex flex-col">
            <label class="mb-2">Name</label>
            <input v-model="form.name" type="text"
                   class="px-3 py-2 max-w-full focus:ring focus:outline-none dark:placeholder-gray-400' border rounded">
        </div>
        <div class="flex flex-col mt-2">
            <label class="mb-2">Priority</label>
            <input v-model="form.priority" type="text"
                   class="px-3 py-2 max-w-full focus:ring focus:outline-none dark:placeholder-gray-400' border rounded">
        </div>
        <div class="flex justify-end items-end">
            <button
                @click="addTask"
                class="cursor-pointer inline-flex justify-center items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border rounded bg-blue-700 p-2 mt-2 text-white mr-2">
                Save
            </button>
            <button
                @click="showAddModal = false"
                class="cursor-pointer inline-flex justify-center items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border rounded bg-blue-300 p-2 mt-2 text-white ">
                Cancel
            </button>
        </div>
    </modal>

    <modal :show-modal="showDeleteModal" @close="showDeleteModal=false">
        <div class="flex items-center justify-center">
            Are you sure you want to delete the <strong>{{ deleteElement.name }}</strong> task?
        </div>
        <div>
            <button
                @click="deleteTask"
                class="cursor-pointer inline-flex justify-center items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border rounded bg-blue-700 p-2 mt-2 text-white">
                Guardar
            </button>
        </div>
    </modal>

    <div class="flex flex-row">
        <div class="w-full h-screen border-r-black">
            <div class="flex justify-around">
                <div class="p-4 flex flex-row items-center justify-cente">
                    <div class="px-3 py-2 mr-2">Tasks</div>
                    <div @click="openAddModal">
                        <font-awesome-icon :icon="['fas', 'plus']"/>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center w-1/3">
                    <label class="px-3 py-2 mb-2 mr-2">Project</label>
                    <select
                        class="px-3 py-2 max-w-full focus:ring focus:outline-none dark:placeholder-gray-400 border rounded mr-2"
                        v-model="selectedProjectId"
                        @change="filterByProject">
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select>
                    <div
                        class="text-black text-lg"
                        @click="clearFilter"
                    >
                        <font-awesome-icon :icon="['fas', 'close']"/>
                    </div>
                </div>
            </div>
            <draggable
                v-model="tasks"
                group="first"
                @start="startDrag"
                @end="endDrag"
                item-key="id"
                class="w-full h-full"
            >
                <template #item="{ element, index }">
                    <div class="p-4 m-4 bg-gray-100 flex justify-between">
                        <div class="flex flex-row items-center">
                            <div>
                                {{ element.name }}
                            </div>
                            <div
                                class="bg-gray-400 rounded-full full justify-center text-center text-white ml-2 py-1 px-2 text-sm">
                                {{ element.project.name }}
                            </div>
                        </div>

                        <div class="flex justify-center items-center">
                            <div
                                class="bg-gray-400 rounded-full w-6 h-6 full justify-center text-center text-white mr-2"
                            >
                                {{ index + 1 }}
                            </div>

                            <div
                                class="text-black text-lg"
                                @click="openDeleteModal(element)"
                            >
                                <font-awesome-icon :icon="['fas', 'trash']"/>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>
