<template>
    <Modal v-model="isShow" width="360" :closable="false" @on-ok="doAdd">
        <p slot="header" style="color:#19be6b;text-align:center">
            <Icon type="md-add-circle" />
            <span>向List插入新值</span>
        </p>
        <div style="text-align:center">
            <Input v-model="indexModel" placeholder="填写插入位置" @on-blur="checkMaxIndex" style="margin-bottom: 10px"></Input>
            <Input v-model="newValue" type="textarea" :rows="5" placeholder="请输入要增加的内容"/>
        </div>
    </Modal>
</template>

<script>
    import {op} from "../../../api/redis";

    export default {
        name: "AddNewListValue",
        data() {
            return {
                indexModel: '',
                newValue: '',
                isShow: false,
                info: {}
            }
        },
        methods: {
            checkMaxIndex() {
                if (this.indexModel > this.info.size - 1) {
                    this.indexModel = this.info.size - 1
                }
            },
            show(info) {
                this.info = info;
                this.isShow = true;
            },
            doAdd() {
                op({key: this.info.key, type: 3, action: 'insert', ops: {index: this.indexModel, value: this.newValue}}).then((resp) => {
                    this.$Message.success('增加成功');
                    this.$emit('add-success', resp.data);
                    this.close();
                }).catch(() => {});
            },
            close() {
                this.info = {};
                this.indexModel = '';
                this.newValue = '';
                this.isShow = false;
            }
        }
    }
</script>

<style scoped>

</style>