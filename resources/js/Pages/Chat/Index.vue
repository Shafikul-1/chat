<script setup>
import { ref, reactive, defineProps } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatInput from '@/Components/Chat/ChatInput.vue';
import ChatText from '@/Components/Chat/ChatText.vue';
import AllUser from '@/Components/Chat/AllUser.vue';
import ChatUser from '@/Components/Chat/ChatUser.vue';
const props = defineProps({
    users: Array,
    message: Array,
    frinds: Array,
});


const chatAllUser = ref(false);
const message = ref('');

const showModal = ref(false);
const openModal = () => {
    showModal.value = true;
}
// console.log(props.frinds);

</script>

<template>
    <AuthenticatedLayout>
        <!-- Modal All User-->
        <AllUser :showModal="showModal" :allUser="props.users"/>
        <!-- Modal All User-->
        <div class="flex-1 dark:bg-[#111827] bg-gray-100 w-full h-full mt-5">
            <div class="main-body container m-auto w-11/12 h-full flex flex-col">
                <div class="main flex-1 flex flex-col">
                    <div class="flex-1 flex h-full relative lg:gap-3">
                        <div :class="{ 'hidden': !chatAllUser }"
                            class="sidebar border-r-indigo-300 border-r absolute w-[95%] left-[2rem] top-0 z-50 lg:flex lg:static lg:w-[35%] min-h-[88vh] overflow-y-auto flex-2 flex-col  bg-gray-700 p-3 lg:bg-transparent ">
                            <div class="search flex-2 pb-6 px-2">
                                <input @click="openModal" type="text"
                                    class="outline-none py-2 block w-full bg-transparent border-b-2 "
                                    placeholder="Search User">
                            </div>

                            <!-- User All -->
                            <template v-if="props.users != null">

                               <ChatUser :allFrinds="props.frinds"/>

                            </template>
                            <div class="text-center dark:text-white font-bold text-4xl" v-else>No Added User</div>
                            <!-- User All -->

                        </div>
                        <template v-if="props.message != null">
                            <div class="chat-area flex-1 flex flex-col">
                                <div class="flex-3">
                                    <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200 dark:text-white">
                                        <span class="lg:hidden">
                                            <i @click="chatAllUser = !chatAllUser"
                                                class="fa-solid fa-bars  mr-5 cursor-pointer w-5 h-5"></i>
                                        </span>
                                        Chatting with
                                        <b>Mercedes Yemelyan</b>
                                    </h2>
                                </div>
                                <div class="messages flex-1 overflow-auto">

                                    <!-- Chat Text -->
                                    <ChatText />
                                    <!-- Chat Text -->
                                </div>


                                <!-- Chat filed Content -->
                                <ChatInput />
                                <!-- Chat filed Content -->
                            </div>
                        </template>
                        <div v-else>
                            <h1 class="font-bold text-xl dark:text-white text-center">No Chat here</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
