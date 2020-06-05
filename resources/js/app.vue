<template>
    <div style="background: url('/images/background.jpeg') no-repeat left 64px;overflow: hidden;background-size: cover;">
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        name: 'App',
        data() {
            return {}
        }
    }
</script>
