<template>
    <Modal v-model="isShow" width="360" :closable="false" @on-ok="doAdd">
        <p slot="header" style="color:#19be6b;text-align:center">
            <Icon type="md-add-circle" />
            <span>插入新值</span>
        </p>
        <div style="text-align:center">
            <Input v-model="indexModel" placeholder="键" style="margin-bottom: 10px"></Input>
            <Input v-model="newValue" type="textarea" :rows="5" placeholder="值"/>
        </div>
    </Modal>
</template>

<script>
    import {op} from "../../../api/redis";

    export default {
        name: "AddHashValue",
        data() {
            return {
                indexModel: '',
                newValue: '',
                isShow: false,
                key: ''
            }
        },
        methods: {
            show(key) {
                this.key = key;
                this.isShow = true;
            },
            doAdd() {
                op({key: this.key, type: 5, action: 'insert', ops: {mkey: this.indexModel, value: this.newValue}}).then((resp) => {
                    this.$Message.success('增加成功');
                    this.$emit('add-success', resp.data);
                    this.close();
                }).catch(() => {});
            },
            close() {
                this.key = '';
                this.indexModel = '';
                this.newValue = '';
                this.isShow = false;
            }
        }
    }
</script>

<style scoped>

</style>