<script setup>

</script>

<template>
    <div
        class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] overflow-auto font-[sans-serif]">
        <div class="w-full max-w-lg bg-white shadow-lg rounded-3xl px-8 py-6 relative">
           <slot/>
        </div>
    </div>
</template>

<style scoped></style>
