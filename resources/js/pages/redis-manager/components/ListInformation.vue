<template>
    <div>
        <div class="redis-key-info">
            <div class="redis-queue-actions">
                <Button type="success" ghost @click="openInsertDialog">新增</Button>
                <ButtonGroup :style="{float: 'right'}">
                    <Button type="primary" ghost @click="refresh" :loading="refreshLoading">刷新</Button>
                    <Button type="error" ghost @click="deleteKey">删除</Button>
                </ButtonGroup>
            </div>
            <Input v-model="info.value" type="textarea" :rows="10" />
            <div style="margin-top: 5px;">
                <Page
                        @on-change="turnPage"
                        :total="info.size"
                        :current="Number(info.index) + 1"
                        :page-size="pageSize"
                        :style="{float:'left'}"
                        v-if="info.size > 1"
                        simple>
                </Page>
                <div class="redis-key-action">
                    <Button type="error" style="margin-right: 10px" @click="deleteItem" :loading="deleteLoading">删除</Button>
                    <Button type="primary" @click="updateByKey" :loading="saveLoading">保存</Button>
                </div>
            </div>
            <AddNewListValue ref="addDialog" @add-success="showNewAddValue"></AddNewListValue>
        </div>
    </div>
</template>

<script>
    import {get, op} from "../../../api/redis";
    import AddNewListValue from './AddNewListValue';

    export default {
        name: "ListInformation",
        components: {AddNewListValue},
        props: {
            searchBox: {}
        },
        data() {
            return {
                type: 0,
                info: {
                    key: '',
                    size: 0,
                    index: 0,
                    value: ''
                },
                oldValue: '',
                pageSize: 1,
                refreshLoading: false,
                deleteLoading: false,
                saveLoading: false
            }
        },
        watch: {
            searchBox: {
                handler(newValue, oldValue) {
                    if (newValue) {
                        this.getKeyInfo(newValue);
                    }
                },
                immediate: true
            }
        },
        methods: {
            getKeyInfo(info) {
                get(info).then(response => {
                    this.info = response.data;
                    this.oldValue = this.info.value;
                }).catch(error => {});
            },
            refresh() {
                this.refreshLoading = true;
                get(this.searchBox).then(response => {
                    this.info = response.data;
                    this.oldValue = this.info.value;
                    this.refreshLoading = false;
                }).catch(error => {});
            },
            deleteKey() {
                this.$emit('delete-by-key')
            },
            deleteItem() {
                this.deleteLoading = true;
                op({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db, action: 'delete', ops: {index: this.info.index, value: this.info.value}}).then((res) => {
                    this.info = res.data;
                    this.deleteLoading = false;
                }).catch(() => {});
            },
            openInsertDialog() {
                this.$refs.addDialog.show(this.info);
            },
            updateByKey() {
                if (this.info.value == this.oldValue) {
                    this.$Message.warning('没有修改任何信息');
                    return ;
                }
                this.saveLoading = true;
                op({
                    key: this.searchBox.key,
                    type: this.searchBox.type,
                    db: this.searchBox.db,
                    action: 'update',
                    ops: {
                        index: this.info.index,
                        value: this.info.value
                    }
                }).then(() => {
                    this.saveLoading = false;
                    this.$Message.success('保存成功');
                }).catch(() => {});
            },
            turnPage(pageNo) {
                this.info.index = pageNo - 1;
                op({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db, ops: {index: this.info.index}, action: 'get'}).then(response => {
                    this.info.value = response.data.value;
                }).catch(() => {});
            },
            showNewAddValue(data) {
                this.info = data;
            }
        }
    }
</script>

<style scoped>
    .redis-key-info {
        padding: 10px 0 0 10px;
    }
    .redis-key-action {
        display: inline-block;
        float: right;
    }
    .redis-queue-actions {
        margin-bottom: 8px;
    }
    /deep/ .ivu-page-simple-pager input {
        width: 40px;
    }
</style>