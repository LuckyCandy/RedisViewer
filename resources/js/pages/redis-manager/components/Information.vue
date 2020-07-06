<template>
    <div style="height: 100%;position: relative;">
        <div class="key-info-header">
            <span :style="{background: this.getKeyTypeColor(this.info.type).bg, color: this.getKeyTypeColor(this.info.type).font, position: 'unset'}" v-if="info.type > 0">{{ info.key }}</span>
            <Alert type="warning" show-icon v-else>
                请选择要查看的KEY
            </Alert>
        </div>
        <div class="key-info-content">
            <StringInformation ref="content" v-if="info.type === 1" :search-box="info" @delete-by-key="deleteKey"></StringInformation>
            <SetInformation ref="content" v-if="info.type === 2" :search-box="info" @delete-by-key="deleteKey"></SetInformation>
            <ListInformation ref="content" v-if="info.type === 3" :search-box="info" @delete-by-key="deleteKey"></ListInformation>
            <ZSetInformation ref="content" v-if="info.type === 4" :search-box="info" @delete-by-key="deleteKey"></ZSetInformation>
            <HashInformation ref="content" v-if="info.type === 5" :search-box="info" @delete-by-key="deleteKey"></HashInformation>
        </div>
        <Spin size="large" fix v-if="spinShow"></Spin>
    </div>
</template>

<script>
    import StringInformation from './StringInformation';
    import ListInformation from './ListInformation';
    import SetInformation from './SetInformation';
    import {remove} from "../../../api/redis";
    import ZSetInformation from "./ZSetInformation";
    import HashInformation from "./HashInformation";

    export default {
        name: "Information",
        components: {HashInformation, ZSetInformation, StringInformation, ListInformation, SetInformation},
        data() {
            return {
                info: {
                    key: '',
                    type: 0,
                    db: 0
                },
                spinShow: false,
            }
        },
        methods: {
            getKeyInfo(info) {
                this.info = info;
            },
            init() {
                this.info = {
                    key: '',
                    type: 0,
                    db: 0
                };
                this.spinShow = false;
            },
            deleteKey() {
                this.spinShow = true;
                remove(this.info).then(() => {
                    this.init();
                    this.$emit('remove-current-key');
                }).catch(() => {
                    this.spinShow = false;
                })
            },
            getKeyTypeColor(type) {
                switch (type) {
                    case 1:
                        return {
                            bg: '#2d8cf0',
                            font: 'white'
                        };
                    case 2:
                        return {
                            bg: '#f90',
                            font: 'black'
                        };
                    case 3:
                        return {
                            bg: '#19be6b',
                            font: 'white'
                        };
                    case 4:
                        return {
                            bg: '#ed4014',
                            font: 'white'
                        };
                    case 5:
                        return {
                            bg: '#d3adf7',
                            font: 'black'
                        };
                    default:
                        return {
                            bg: '#f7f7f7',
                            font: 'black'
                        }
                }
            }
        }
    }
</script>

<style scoped>
    .key-info-header {
        text-align: center;
        height: 30px;
        position: absolute;
        width: 100%;
    }
    .key-info-header span {
        font-size: 13px;
        white-space: nowrap;
        line-height: 2.5;
        overflow: hidden;
        display: block;
    }
    .key-info-content {
        position: absolute;
        width: 100%;
        top: 30px;
        bottom: 0;
    }
</style>