<template>
    <div>
        <div class="redis-key-info">
            <Input v-model="info.value" type="textarea" :rows="8" placeholder="Enter something..." />
            <div style="margin-top: 5px;">
                <span style="font-size: 12px;color: gray;">{{ this.getDateStr(info.ttl) }}</span>
                <div class="redis-key-action">
                    <Button type="error" style="margin-right: 10px" @click="deleteKey">删除</Button>
                    <Button type="primary" @click="updateByKey">保存</Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, op} from "../../../api/redis";
    import {secondToDateStr} from "../../../untils";

    export default {
        name: "StringInformation",
        props: {
            searchBox: {}
        },
        computed: {

        },
        data() {
            return {
                type: 0,
                info: {
                    key: '',
                    ttl: '',
                    value: ''
                },
                oldValue: ''
            }
        },
        watch: {
            searchBox: {
                handler(newValue, oldValue) {
                    console.log(newValue);
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
            getDateStr(msd) {
                if (msd == -1) {
                    return '有效期: 永久';
                } else {
                    return '距离失效时间还有:'+secondToDateStr(msd);
                }
            },
            deleteKey() {
                this.$emit('delete-by-key')
            },
            updateByKey() {
                if (this.oldValue == this.info.value) {
                    this.$Message.warning('没有修改任何信息');
                    return;
                }
                op({
                    key: this.info.key,
                    type: this.searchBox.type,
                    db: this.searchBox.db,
                    action: 'update',
                    ops: {
                        ttl: this.info.ttl,
                        value: this.info.value
                    }
                }).then(() => {
                    this.$Message.success('保存成功');
                }).catch(() => {});
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
</style>