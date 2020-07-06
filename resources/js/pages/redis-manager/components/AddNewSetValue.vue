<template>
    <Modal v-model="isShow" width="360" :closable="false" @on-ok="doAdd">
        <p slot="header" style="color:#19be6b;text-align:center">
            <Icon type="md-add-circle" />
            <span>增加新值</span>
        </p>
        <div style="text-align:center">
            <Input v-model="newValue" type="textarea" :rows="5" placeholder="请输入要增加的内容"/>
        </div>
    </Modal>
</template>

<script>
    import {op} from "../../../api/redis";

    export default {
        name: "AddNewSetValue",
        data() {
            return {
                key: '',
                newValue: '',
                isShow: false,
            }
        },
        methods: {
            show(key) {
                this.key = key;
                this.isShow = true;
            },
            doAdd() {
                op({key: this.key, type: 2, action: 'insert', ops: {value: this.newValue}}).then(() => {
                    this.$Message.success('增加成功');
                    this.$emit('add-success', this.newValue);
                    this.close();
                }).catch(() => {});
            },
            close() {
                this.key = '';
                this.newValue = '';
                this.isShow = false;
            }
        }
    }
</script>

<style scoped>

</style>