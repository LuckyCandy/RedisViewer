<template>
    <div style="height: 100%">
        <div class="redis-client-search">
            <div class="redis-client-search-area">
                <Select v-model="searchBox.db" style="width:55px" placeholder="DB">
                    <Option :value="0">0</Option>
                    <Option v-for="db_no in 19" :value="db_no" :key="db_no">{{ db_no }}</Option>
                </Select>
                <Input v-model="searchBox.key_pattern" placeholder="按键查询" clearable :style="{width: '180px'}" />
                <Button
                        icon="ios-search"
                        type="primary"
                        shape="circle"
                        @click="startSearch"
                        :loading="isSearchLoading"
                        :style="{margin: '0 6px'}">
                </Button>
                <div class="serve-action">
                    <Divider type="vertical" />
                    <Tag color="primary">string</Tag>
                    <Tag color="warning">set</Tag>
                    <Tag color="error">zset</Tag>
                    <Tag color="purple">hash</Tag>
                    <Tag color="success">list</Tag>
                    <Tag color="default">null</Tag>
                    <Divider type="vertical" />
                    <span style="color: gray; font-size: 12px"> * 为确保性能，最多只展示500条哦~ </span>
                </div>
            </div>
            <Split v-model="splitRate" :style="{position: 'absolute', top: '50px', bottom: '0'}" min="180" max="180">
                <div slot="left">
                    <div class="redis-client-search-content">
                        <Table
                                :show-header="false"
                                :columns="column"
                                :data="searchData"
                                :loading="isSearchLoading" size="small" no-data-text="暂无内容"
                                @on-row-click="onRowClicked"
                                :row-class-name="rowSelectStyle"
                                stripe
                        >
                        </Table>
                    </div>
                </div>
                <div slot="right" style="padding-top: 5px;height: 100%;margin-right: 10px;">
                    <Information ref="information" @remove-current-key="removeCurrentKey"></Information>
                </div>
            </Split>
        </div>
        <div class="redis-client-info"></div>
    </div>
</template>

<script>
    import {keys} from '../../../api/redis';
    import Information from './Information';

    export default {
        name: "ActionTable",
        components: {Information},
        data() {
            return {
                isSearchLoading: false,
                pageMax: 20,
                splitRate: 0.5,
                searchBox: {
                    db: 0,
                    key_pattern: '',
                },
                selectedRow: '',
                searchData: [],
                column: [
                    {
                        title: 'Name',
                        key: 'key',
                        render: (h, params) => {
                            return h('div', [
                                h('span', {
                                    style: {
                                        border: '7px solid',
                                        borderColor: 'transparent transparent transparent ' + this.getKeyTypeColor(params.row.type)
                                    }
                                }),
                                h('span', {
                                    style: {
                                        whiteSpace: 'nowrap',
                                        overflow: 'hidden',
                                        lineHeight: '29px',
                                        fontSize: '15px'
                                    }
                                }, params.row.key)
                            ]);
                        }
                    }
                ]
            }
        },
        created() {
            this.startSearch();
        },
        methods: {
            startSearch() {
                this.isSearchLoading = true;
                this.$refs.information && this.$refs.information.init();
                keys(this.searchBox).then((response) => {
                    this.searchData = response.data;
                    this.selectedRow = '';
                    this.isSearchLoading = false;
                }).catch(() => {
                    this.isSearchLoading = false;
                })
            },
            onRowClicked(row, index) {
                this.selectedRow = index;
                this.$refs.information.getKeyInfo({
                    key: row.key,
                    db: this.searchBox.db,
                    type: row.type
                });
            },
            removeCurrentKey() {
                let tmpData = this.searchData;
                this.searchData = [];
                for(let index = 0; index < tmpData.length; index++) {
                    if (index != this.selectedRow) {
                        this.searchData.push(tmpData[index]);
                    }
                }
                this.selectedRow = '';
            },
            getKeyTypeColor(type) {
                switch (type) {
                    case 1:
                        return '#2d8cf0';
                    case 2:
                        return '#f90';
                    case 3:
                        return '#19be6b';
                    case 4:
                        return '#ed4014';
                    case 5:
                        return '#d3adf7';
                    default:
                        return '#f7f7f7'
                }
            },
            rowSelectStyle(row, index) {
                if (index === this.selectedRow) {
                    return 'custom_selected_row';
                }
            }
        }
    }
</script>

<style scoped>
    .redis-client-search {
        width: 100%;
        height: 100%;
        float: left;
        position: relative;
    }
    .redis-client-info {
        width: 70%;
        height: 100%;
        float: left;
    }
    .redis-client-search-area {
        background: white;
        border-radius: 5px;
        height: 50px;
        width: 99%;
        min-width: 835px;
        padding: 10px;
        position: absolute;
        top: 0
    }
    .redis-client-search-content {
        overflow-y: scroll;
        padding-top: 5px;
        position: absolute;
        top: 0;
        bottom: 5px;
        height: auto;
        width: 100%;
    }
    .serve-action {
        display: inline-block;
    }
    /deep/ .custom_selected_row {
        color: #ee4015;
    }
    /deep/ .ivu-table-cell {
        padding: 0;
    }
    /deep/ .ivu-table-small td {
        height: 30px;
    }
    /deep/ .ivu-split-wrapper {
        height: auto;
    }
</style>