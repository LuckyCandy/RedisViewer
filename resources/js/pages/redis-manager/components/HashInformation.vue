<template>
    <div class="redis-key-info">
        <div class="redis-queue-actions">
            <div class="redis-show-header">
                <span>总数: {{ total }}</span>
                <ButtonGroup :style="{float: 'right'}">
                    <Button type="success" ghost @click="openInsertDialog" size="small">新增</Button>
                    <Button type="error" ghost @click="deleteKey" size="small">删除</Button>
                </ButtonGroup>
            </div>
        </div>
        <div class="redis-show-content">
            <Input v-model="showRowContent" type="textarea" :rows="3" placeholder="点击列表行，可完成查看" style="resize:none"/>
            <Button type="text" size="small" ghost icon="ios-expand" class="redis-key-content-full" @click="openBigShow"></Button>
        </div>
        <div class="redis-key-list">
            <Table
                    @on-row-click="changeShowContent"
                    :columns="tableColumns"
                    :data="collect"
                    size="small"
                    border
                    :loading="isTableLoading"
                    :show-header="false">
                <template slot-scope="{ row, index }" slot="key">
                    <span style="padding: 0 10px;font-size: 15px;white-space: nowrap;overflow: hidden;">
                        {{ row.key }}
                    </span>
                </template>
                <template slot-scope="{ row, index }" slot="value">
                    <Input v-model="rowValue" v-if="editIndex === index" size="small"/>
                    <span style="font-size: 15px;white-space: nowrap;overflow: hidden;" v-else>
                        {{ row.value }}
                    </span>
                </template>
                <template slot-scope="{ row, index }" slot="action">
                    <div v-if="editIndex === index">
                        <Button type="success" size="small" @click="updateValue(index)" ghost icon="md-checkmark"></Button>
                        <Button type="warning" size="small" @click="editIndex = -1;" ghost icon="md-close"></Button>
                    </div>
                    <div v-else>
                        <Button type="primary" size="small" @click="enableEdit(index)" ghost icon="ios-nutrition"></Button>
                        <Button type="error" size="small" @click="deleteItem(index)" ghost icon="md-close"></Button>
                    </div>

                </template>
            </Table>
        </div>
        <FullScreenShow ref="bigShow" :content="showRowContent"></FullScreenShow>
        <AddHashValue ref="addNewKeyValue" @add-success="addSuccess"></AddHashValue>
    </div>
</template>

<script>
    import {get, op} from "../../../api/redis";
    import FullScreenShow from "../../../components/FullScreenShow";
    import AddHashValue from "./AddHashValue";

    export default {
        name: "HashInformation",
        components: {AddHashValue, FullScreenShow},
        props: {
            searchBox: {}
        },
        data() {
            return {
                type: 5,
                rowValue: '',
                showRowContent: '',
                editIndex: -1,
                refreshLoading: false,
                total: 0,
                collect:[],
                isTableLoading: false,
                tableColumns: [
                    {
                        title: 'key',
                        slot: 'key',
                        width: 100,
                        align: 'center'
                    },
                    {
                        title: 'value',
                        slot: 'value',
                        tooltip: true
                    },
                    {
                        title: 'Action',
                        slot: 'action',
                        align: 'center',
                        width: 80

                    }
                ],
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
                this.isTableLoading = true;
                get(info).then(response => {
                    this.isTableLoading = false;
                    this.collect = response.data;
                    this.total = this.collect.length;
                }).catch(error => {});
            },
            refresh() {
                this.refreshLoading = true;
                this.isTableLoading = true;
                get({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db
                }).then(response => {
                    this.collect = response.data;
                    this.total = this.collect.length;
                    this.refreshLoading = false;
                    this.isTableLoading = false;
                }).catch(error => {});
            },
            deleteKey() {
                this.$emit('delete-by-key')
            },
            enableEdit(index) {
                this.editIndex = index;
                this.rowValue = this.collect[index].value;
            },
            deleteItem(index) {
                this.isTableLoading = true;
                op({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db, action: 'delete', ops: {mkey: this.collect[index]['key']}}).then(() => {
                    this.collect.splice(index, 1);
                    this.isTableLoading = false;
                }).catch(() => {});
            },
            changeShowContent(row) {
                this.showRowContent = row.key + ' : ' + row.value;
            },
            openInsertDialog() {
                this.$refs.addNewKeyValue.show(this.viewKey);
            },
            addSuccess(data) {
                this.collect.unshift(data);
            },
            updateValue(index) {
                let item = this.collect[index];
                this.isTableLoading = true;
                op({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db, action: 'update', ops: {mkey: item.key, value: this.rowValue}}).then(() => {
                    this.collect[index]['value'] = this.rowValue;
                    this.isTableLoading = false;
                    this.editIndex = -1;
                }).catch(() => {
                    this.editIndex = -1;
                });
            },
            openBigShow() {
                this.$refs.bigShow.open(this.showRowContent);
            }
        }
    }
</script>

<style scoped>
    .redis-key-info {
        padding: 10px 0 0 10px;
        position: relative;
        height: 100%;
    }
    .redis-queue-actions {
        width: 100%;
        top: 5px;
        height: 35px;
        position: absolute;
        padding: 5px 10px 5px 5px;
        border-radius: 3px;
    }
    .redis-show-content {
        position: absolute;
        top: 40px;
        width: 100%;
        height: 75px;
        padding-right: 10px;
    }
    .redis-key-list {
        position: absolute;
        top: 116px;
        bottom: 0;
        right: 0;
        overflow: scroll;
        left: 10px;
    }
    .redis-key-content-full {
        position: absolute;
        color: black;
        right: 6px;
        top: -3px;
    }
    /deep/ .ivu-input-type-textarea textarea {
        resize: none;
    }
</style>