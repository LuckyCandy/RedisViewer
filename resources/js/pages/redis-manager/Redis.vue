<template>
    <div style="width:100%;padding: 10px;font-size: 15px">
        <Breadcrumb :style="{fontSize: '15px'}">
            <BreadcrumbItem to="/dashboard">
                <Icon type="md-apps"></Icon> Dashboard
            </BreadcrumbItem>
            <BreadcrumbItem>
                <Icon custom="iconfont iconredis"></Icon> Redis
            </BreadcrumbItem>
        </Breadcrumb>
        <div class="redis-client" style="overflow-y: scroll">
            <Card :style="{width: '200px',margin: '10px', float: 'left'}" :padding="1" v-for="(client, index) in clients" :key="index" :to="{name: 'redis-action', params: client}">
                <p slot="title" style="color: #ffffff;">{{ client.name }}</p>
                <a href="#" slot="extra" @click.prevent.stop="update(client)">
                    <Icon type="ios-hammer" />
                    修改
                </a>
                <div>
                    <p style="text-align: center;font-size: 20px;text-overflow:ellipsis;white-space: nowrap;overflow: hidden">{{ client.address }}</p>
                    <p style="font-size: 20px">
                        <span class="config" style="border-right: 1px dashed">{{ client.port }}</span>
                        <span class="config">{{ client.db }}</span>
                    </p>
                </div>
            </Card>
            <Card :style="{width: '95px', height: '95px',margin: '10px', float: 'left'}" :padding="1">
                <Button :style="{width: '100%', height: '90px', backgroundColor: '#f0f3f7'}" @click="addNewClient">
                    <img src="/images/redis-add-client.png" width="100%">
                </Button>
            </Card>
        </div>

        <!-- 更新|增加弹框 -->
        <CreateUpdateClient ref="cu" @refresh="refreshList"></CreateUpdateClient>
    </div>
</template>

<script>
    import CreateUpdateClient from './components/CreateUpdateClient';
    import {list} from '../../api/redis-client';

    export default {
        name: "Redis",
        components: {CreateUpdateClient},
        comments: {CreateUpdateClient},
        data() {
            return {
                clients: []
            }
        },
        created() {
            this.getClientList();
        },
        methods: {
            update(item) {
                this.$refs.cu.openForUpdate(item);
            },
            addNewClient() {
                this.$refs.cu.openForCreate();
            },
            getClientList() {
                list().then((response) => {
                    this.clients = response.data;
                }).catch(() => {})
            },
            refreshList() {
                this.getClientList();
            }
        }
    }
</script>

<style>
    .redis-client .ivu-card-head {
        line-height: 1px;
        padding: 5px;
        background: #2f78ec;
        border-radius: 3px;
    }
    .redis-client .ivu-card-extra {
        top: 5px;
        right: 10px;
        position: absolute;
    }
    .redis-client .ivu-card-extra a {
        color: white;
    }

    .redis-client .config {
        display: block;
        width: 50%;
        float: left;
        text-align: center;
        color: gray;
        border-top: 1px dashed;
    }
</style>
