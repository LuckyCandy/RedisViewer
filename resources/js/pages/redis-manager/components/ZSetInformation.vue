<template>
    <div class="redis-key-info">
        <div class="redis-queue-actions">
            <Input type="text" number v-model="minScore" size="small" style="width: 50px"></Input>
            <span>-</span>
            <Input type="text" number v-model="maxScore" size="small" style="width: 50px"></Input>
            <Button type="primary" @click="refresh" :loading="refreshLoading" size="small">查询</Button>
            <ButtonGroup :style="{float: 'right'}">
                <Button type="success" ghost @click="openInsertDialog" size="small">新增</Button>
                <Button type="error" ghost @click="deleteKey" size="small">删除</Button>
            </ButtonGroup>
        </div>
        <div class="redis-key-content">
            <div class="redis-key-list">
                <Table
                        :columns="tableColumns"
                        :data="collect"
                        size="small"
                        border
                        :show-header="false">
                    <template slot-scope="{ row, index }" slot="score">
                        <Input type="number" v-model="rowScore" v-if="editIndex === index" size="small"/>
                        <span style="padding: 0 10px;font-size: 15px;" v-else>{{ row.score }}</span>
                    </template>
                    <template slot-scope="{ row, index }" slot="content">
                        <span style="padding: 0 10px;font-size: 15px;">{{ row.content }}</span>
                    </template>
                    <template slot-scope="{ row, index }" slot="action">
                        <div v-if="editIndex === index">
                            <Button type="success" size="small" @click="addScore(index)" ghost>保存</Button>
                            <Button type="warning" size="small" @click="editIndex = -1;rowScore=0" ghost>取消</Button>
                        </div>
                        <div v-else>
                            <Button type="primary" size="small" @click="editIndex = index" ghost>加分</Button>
                            <Button type="error" size="small" @click="deleteItem(index)" ghost>删除</Button>
                        </div>
                    </template>
                </Table>
            </div>
            <div class="redis-key-count" v-show="total > pageSize">
                <Page :total="total" :page-size="pageSize" :current-page="currentPage" @on-change="turnPage"/>
            </div>
        </div>
        <AddZSetValue ref="addDialog" @add-success="addSuccess"></AddZSetValue>
    </div>
</template>

<script>
    import {get, op} from "../../../api/redis";
    import AddZSetValue from "./AddZSetValue";

    export default {
        name: "ZSetInformation",
        components: {AddZSetValue},
        props: {
            searchBox: {}
        },
        data() {
            return {
                type: 4,
                minScore: 0,
                maxScore: 100,
                rowScore: 0,
                editIndex: -1,
                pageSize: 10,
                currentPage: 1,
                refreshLoading: false,
                total: 0,
                collect:[],
                tableColumns: [
                    {
                        title: 'Score',
                        slot: 'score',
                        width: 100,
                        align: 'center'
                    },
                    {
                        title: 'content',
                        slot: 'content',
                        tooltip: true
                    },
                    {
                        title: 'Action',
                        slot: 'action',
                        align: 'center',
                        width: 100
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
                get({key: info.key, type: info.type,db: info.db, ops: {
                        min_score: this.minScore,
                        max_score: this.maxScore,
                        page_size: this.pageSize,
                        page_no: this.currentPage
                    }
                }).then(response => {
                    this.collect = response.data.result;
                    this.total = response.data.total;
                }).catch(error => {});
            },
            refresh() {
                this.refreshLoading = true;
                get({key: this.searchBox.key, type: this.searchBox.type,db: this.searchBox.db, ops: {
                        min_score: this.minScore,
                        max_score: this.maxScore,
                        page_size: this.pageSize,
                        page_no: this.currentPage
                    }
                }).then(response => {
                    this.collect = response.data.result;
                    this.total = response.data.total;
                    this.refreshLoading = false;
                }).catch(error => {});
            },
            deleteKey() {
                this.$emit('delete-by-key')
            },
            deleteItem(index) {
                let value = this.collect[index].content;
                op({key: this.searchBox.key, type: this.searchBox.type,db: this.searchBox.db, action: 'delete', ops: {value: value}}).then(() => {
                    this.refresh();
                }).catch(() => {});
            },
            addScore(index) {
                let item = this.collect[index];
                op({key: this.searchBox.key, type: this.searchBox.type,db: this.searchBox.db,  action: 'add', ops: {value: item.content, score: this.rowScore}}).then(() => {
                    this.collect[index].score += Number(this.rowScore);
                    this.editIndex = -1;
                }).catch(() => {});
            },
            turnPage(pageNo) {
                this.currentPage = pageNo;
                this.refresh();
            },
            openInsertDialog() {
                this.$refs.addDialog.show(this.viewKey);
            },
            addSuccess() {
                this.refresh();
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
        background: white;
        vertical-align: middle;
        padding: 5px 5px;
        border-radius: 3px;
    }
    .redis-key-content {
        padding-right: 10px;
        position: absolute;
        top: 45px;
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