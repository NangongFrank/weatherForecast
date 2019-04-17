<template>
    <div>
        <router-view />
    </div>
</template>
<script>
    export default {

    }
</script>

<style lang="less">
    @import "./config";
    *{
        margin: 0;
        padding: 0;
    }
    img{
        border:0;
    }
    ol, ul ,li{list-style: none;}
    .page {
        display: flex;
        @{jc}: flex-end;
        padding: 20px 0;
        margin-right: 100px;
    }
</style>
