<template>
    <div style="width:100%;padding: 10px;font-size: 15px">
        <Breadcrumb :style="{fontSize: '15px'}">
            <BreadcrumbItem to="/dashboard">
                <Icon type="md-apps"></Icon> Dashboard
            </BreadcrumbItem>
            <BreadcrumbItem to="/redis">
                <Icon custom="iconfont iconredis"></Icon> Redis
            </BreadcrumbItem>
            <BreadcrumbItem>
                {{ this.$route.params.name }}
            </BreadcrumbItem>
        </Breadcrumb>
        <div class="action-content">
            <div class="action-area">
                <ActionTable ref="actionTable"></ActionTable>
            </div>
            <div class="client-info">
                <Collapse v-model="collapseName" simple>
                    <Panel name="1">
                        基本信息
                        <List slot="content" size="small">
                            <ListItem><span class="title">当前版本:</span><span class="value">{{ clientInfo.redis_version }}</span></ListItem>
                            <ListItem><span class="title">运行模式:</span><span class="value">{{ clientInfo.redis_mode == 'standalone' ? '单机' : '集群' }}</span></ListItem>
                            <ListItem><span class="title">已运行:</span><span class="value">{{ clientInfo.uptime_in_days }}天</span></ListItem>
                            <ListItem>
                                <span class="title">启动命令:</span>
                                <Tooltip :content="clientInfo.executable">
                                    <span class="value">{{ clientInfo.executable }}</span>
                                </Tooltip>
                            </ListItem>
                            <ListItem>
                                <span class="title">配置文件:</span>
                                <Tooltip :content="clientInfo.config_file">
                                    <span class="value">{{ clientInfo.config_file }}</span>
                                </Tooltip>
                            </ListItem>
                        </List>
                    </Panel>
                    <Panel name="2">
                        连接信息
                        <List slot="content" size="small">
                            <ListItem><span class="title">连接数:</span><span class="value">{{ clientInfo.connected_clients }}</span></ListItem>
                            <ListItem><span class="title">阻塞数:</span><span class="value">{{ clientInfo.blocked_clients }}</span></ListItem>
                        </List>
                    </Panel>
                    <Panel name="3">
                        内存
                        <List slot="content" size="small">
                            <ListItem><span class="title">分配内存总量:</span><span class="value">{{ clientInfo.used_memory_rss_human }}</span></ListItem>
                            <ListItem><span class="title">内存消耗峰值:</span><span class="value">{{ clientInfo.used_memory_peak_human }}</span></ListItem>
                            <ListItem><span class="title">内部开销:</span><span class="value">{{ clientInfo.used_memory_overhead }}</span></ListItem>
                            <ListItem><span class="title">整个系统内存:</span><span class="value">{{ clientInfo.total_system_memory_human }}</span></ListItem>
                            <ListItem><span class="title">最大内存设置:</span><span class="value">{{ clientInfo.maxmemory_human }}</span></ListItem>
                        </List>
                    </Panel>
                    <Panel name="4">
                        其它
                        <List slot="content" size="small">
                            <ListItem><span class="title">新建连接数:</span><span class="value">{{ clientInfo.total_connections_received }}</span></ListItem>
                            <ListItem><span class="title">每秒命令数:</span><span class="value">{{ clientInfo.instantaneous_ops_per_sec }}</span></ListItem>
                            <ListItem><span class="title">网络入口kps:</span><span class="value">{{ clientInfo.instantaneous_input_kbps }}</span></ListItem>
                            <ListItem><span class="title">网络出口kps:</span><span class="value">{{ clientInfo.instantaneous_input_kbps }}</span></ListItem>
                            <ListItem><span class="title">拒绝连接数:</span><span class="value">{{ clientInfo.rejected_connections }}</span></ListItem>
                            <ListItem><span class="title">过期比率:</span><span class="value">{{ clientInfo.expired_stale_perc }}</span></ListItem>
                        </List>
                    </Panel>

                </Collapse>
            </div>
        </div>
    </div>
</template>

<script>
    import {set_on_work} from '../../api/redis-client';
    import ActionTable from './components/ActionTable';

    export default {
        name: "Redis-Action",
        components: {ActionTable},
        comments: {ActionTable},
        data() {
            return {
                collapseName: ['1', '2', '3'],
                clientInfo:{}
            }
        },
        created() {
            this.setWorkClient();
        },
        methods: {
            setWorkClient() {
                set_on_work(this.$route.params.id).then((response) => {
                    this.clientInfo = response.data;
                }).catch(() => {
                    setTimeout(() => {
                        this.$router.go(-1);
                    }, 500);
                })
            }
        }
    }
</script>

<style scoped>
    .action-content {
        height: 98%;
        padding-top: 10px;
        display: flex;
    }
    .action-area {
        height: 100%;
        min-width: 875px;
        width: 75%;
        float: left;
    }
    .client-info {
        height: 100%;
        width: 25%;
        min-width: 210px;
        float: left;
        overflow-y: scroll;
    }
    .client-info .title {
        font-weight: 500;
        width: 120px;
    }
    .client-info .value {
        overflow: hidden;
    }
    /deep/ .ivu-tooltip-inner {
        white-space: normal;
    }
</style>