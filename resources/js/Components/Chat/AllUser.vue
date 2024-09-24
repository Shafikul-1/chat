<script setup>
import { ref, reactive, defineProps } from 'vue'
import CustomModal from '../CustomModal.vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    waitLoad: Boolean,
    showModal: Boolean,
    allUser: Array
});

const emit = defineEmits(['closeModal']);
const hideModal = () => {
    emit('closeModal');
}
// const data = Inertia.get(route('check'));
// console.log(data);

</script>

<template>
    <CustomModal class="absolute top-0 left-0" v-if="props.showModal">
        <div class="flex items-start">
            <div class="flex-1">
                <h3 class="text-gray-800 text-2xl font-bold">Members</h3>
                <p class="text-gray-500 text-sm mt-1">Manage and control who has access to this workspace.</p>
            </div>

            <i class="fa-solid fa-xmark cursor-pointer text-2xl" @click="hideModal"> </i>
        </div>

        <div class="flex flex-wrap gap-4 mt-6">
            <div
                class="flex flex-1 px-4 py-2.5 rounded-lg border border-gray-300 focus-within:border-blue-600 min-w-[220px]">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="email" placeholder="Search by name or user id"
                    class="w-full outline-none bg-transparent text-gray-500 text-sm focus:outline-none border-none focus:border-none ring-0" />
            </div>

        </div>

        <template v-if="waitLoad">
            <h3 class="text-center font-bold text-4xl">Loding ....</h3>
        </template>
        <!-- All User -->
        <div v-else class="mt-6 divide-y" v-for="(user, index) in props.allUser" :key="index.id">
            <div class="flex flex-wrap items-center gap-4 py-3">
                <img src='https://readymadeui.com/team-1.webp' class="w-11 h-11 rounded-full" />
                <div>
                    <p class="text-sm text-gray-800 font-bold">{{ user.name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ user.email }}</p>
                </div>
                <Link :href="route('chat.invite', user.id)" as="button" method="post"
                    class="text-xs  mt-0.5 ml-auto bg-indigo-500 px-4 py-1 rounded-md hover:bg-indigo-300 text-white hover:text-black cursor-pointer">
                Invite
                </Link>
            </div>
        </div>
        <!-- All User -->
    </CustomModal>
</template>

<style scoped></style>
