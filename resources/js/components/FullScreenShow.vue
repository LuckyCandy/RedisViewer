<template>
    <Modal v-model="isShow" fullscreen footer-hide>
        <div class="show-content">
            <Input v-model="contentModel" type="textarea"></Input>
        </div>
        <span style="position: absolute;bottom: 10px;width: 100%;text-align: center;color: papayawhip;">
            按键`esc`退出全屏
        </span>
    </Modal>
</template>

<script>
    export default {
        name: "FullScreenShow",
        data() {
            return {
                isShow: false,
                contentModel: ''
            }
        },
        methods: {
            open(value) {
                this.isShow = true;
                this.contentModel = value;
            },
            close() {
                this.isShow = false;
            }
        }
    }
</script>

<style scoped>
    .show-content {
        height: 100%;
        border: 3px inset;
        background: black;
    }
    /deep/ .ivu-input-type-textarea {
        height: 100%;
    }
    /deep/ .ivu-input-type-textarea textarea {
        resize: none;
        font-size: 20px;
        background: transparent;
        color: white;
        height: 100%;
        border: none;
    }
    /deep/ .ivu-modal-body {
        padding: 3px;
    }
    /deep/ .ivu-modal-close {
        display: none;
    }
</style>