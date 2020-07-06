<template>
    <div class="redis-key-info">
        <div class="redis-queue-actions">
            <Button type="success" ghost @click="openInsertDialog">新增</Button>
            <ButtonGroup :style="{float: 'right'}">
                <Button type="primary" ghost @click="refresh" :loading="refreshLoading">刷新</Button>
                <Button type="error" ghost @click="deleteKey">删除</Button>
            </ButtonGroup>
        </div>
        <div class="redis-key-content">
            <div class="redis-key-list">
                <Alert
                        closable
                        v-for="(item, index) in collect"
                        :type="getColorOfIndex(index)"
                        :key="index"
                        style="margin-bottom: 3px"
                        @on-close="deleteItem(index, item)">
                    {{ item }}
                </Alert>
            </div>
            <div class="redis-key-count">
                <span style="font-size: 14px">总数: {{ collect.length }}</span>
            </div>
        </div>

        <AddNewSetValue ref="addDialog" @add-success="addSuccess"></AddNewSetValue>
    </div>
</template>

<script>
    import {get, op} from "../../../api/redis";
    import AddNewSetValue from "./AddNewSetValue";


    export default {
        name: "SetInformation",
        components: {AddNewSetValue},
        props: {
            searchBox: {}
        },
        data() {
            return {
                type: 2,
                collect:[],
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
                    this.collect = response.data;
                }).catch(error => {});
            },
            refresh() {
                this.refreshLoading = true;
                get(this.searchBox).then(response => {
                    this.collect = response.data;
                    this.refreshLoading = false;
                }).catch(error => {});
            },
            deleteKey() {
                this.$emit('delete-by-key')
            },
            deleteItem(index, value) {
                op({key: this.searchBox.key, type: this.searchBox.type, db: this.searchBox.db, action: 'delete', ops: {value: value}}).then(() => {
                    this.collect.splice(index, 1)
                }).catch(() => {});
            },
            openInsertDialog() {
                this.$refs.addDialog.show(this.viewKey);
            },
            addSuccess(value) {
                this.collect.unshift(value);
            },
            getColorOfIndex(index) {
                if (index % 4 == 0) {
                    return 'success';
                } else if (index % 4 == 1) {
                    return 'warning';
                } else if (index % 4 == 2) {
                    return 'error';
                } else {
                    return 'info';
                }
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
        height: 32px;
    }
    .redis-key-content {
        padding-right: 10px;
        position: absolute;
        top: 50px;
        bottom: 50px;
        width: 100%;
    }
    .redis-key-list {
        overflow-y: scroll;
        height: 100%;
    }
    .redis-key-count {
        margin-top: 10px;
        text-align: right;
        width: 100%;
    }
</style>