<template>
    <div style="width: 100%">
        <div style="height: 100%;" v-if="isShowInOneCol">
            <div>
                <Card>
                    <p slot="title">
                        <Icon type="ios-film-outline"></Icon>
                        个人信息
                    </p>
                    <a href="#" slot="extra" @click.prevent="openUserModifyModal">
                        <Icon type="ios-loop-strong"></Icon>
                        修改
                    </a>
                    <List>
                        <ListItem>
                            <ListItemMeta avatar="/images/icon-person.png" title="昵称" :description="userInfo.name" />
                        </ListItem>
                        <ListItem>
                            <ListItemMeta avatar="/images/icon-email.png" title="账号" :description="userInfo.email" />
                        </ListItem>
                    </List>
                </Card>
                <Memorabilia></Memorabilia>
            </div>
            <div style="margin-top: 5px;background: white">
                <List>
                    <ListItem v-for="(product, index) in products" :key="index">
                        <ListItemMeta :avatar="product.icon" :title="product.title" :description="product.desc" />
                        <template slot="action">
                            <li>
                                <router-link :to="product.to">进入</router-link>
                            </li>
                        </template>
                    </ListItem>
                </List>
            </div>
        </div>
        <Row style="height: 100%" v-else>
            <Col :xs="11" :sm="10" :md="14" :lg="15" :xl="17" :xxl="19" style="height: 100%">
                <div class="product-content">
                    <List :style="{background: 'white'}">
                        <router-link v-for="(product, index) in products" :key="index" :to="product.to">
                            <ListItem>
                                <ListItemMeta :avatar="product.icon" :title="product.title" :description="product.desc" />
                            </ListItem>
                            <span></span>
                        </router-link>
                    </List>
                </div>
            </Col>
            <Col :xs="13" :sm="14" :md="10" :lg="9" :xl="7" :xxl="5" style="height: 100%">
                <div class="other-content">
                    <Card>
                        <p slot="title">
                            <Icon type="ios-film-outline"></Icon>
                            个人信息
                        </p>
                        <a href="#" slot="extra" @click.prevent="openUserModifyModal">
                            <Icon type="ios-loop-strong"></Icon>
                            修改
                        </a>
                        <List>
                            <ListItem>
                                <ListItemMeta avatar="/images/icon-person.png" title="昵称" :description="userInfo.name" />
                            </ListItem>
                            <ListItem>
                                <ListItemMeta avatar="/images/icon-email.png" title="账号" :description="userInfo.email" />
                            </ListItem>
                        </List>
                    </Card>
                    <Memorabilia></Memorabilia>
                </div>
            </Col>
        </Row>

        <ModifyUserInfo ref="modifyModal" @refreshUserName="updateUserName"></ModifyUserInfo>
    </div>
</template>

<script>
    import ModifyUserInfo from './components/ModifyUserInfo';
    import Memorabilia from './components/Memorabilia';
    import {getInfo} from "../../api/user";

    export default {
        name: 'Dashboard',
        components: {ModifyUserInfo, Memorabilia},
        data() {
            return {
                userInfo: {},
                products: [
                    {
                        desc: 'Redis数据库查询工具，告别枯燥的命令行！',
                        title: 'Redis Manager',
                        to: {name: 'redis'},
                        icon: '/images/product-redis.png'
                    },
                    {
                        desc: '自动化Api测试工具，接口调试就是这么简单',
                        title: 'Auto Test',
                        to: {name: 'redis'},
                        icon: '/images/product-worker.png'
                    }
                ],
                isShowInOneCol: false
            }
        },
        mounted() {
            window.onresize = () => {
                return (() => {
                    this.isShowInOneCol = document.body.clientWidth < 640;
                })();
            }
        },
        created() {
            this.isShowInOneCol = document.body.clientWidth < 640;
            /* 获取登录人信息 */
            getInfo().then(response => {
                this.userInfo = response.data;
                this.userInfo.avatar = '/images/login.png';
            }).catch(() => {
                EBUS.$emit('EVENT-USER-UNLOGIN');
            });
        },
        beforeCreate() {
            EBUS.$on('EVENT-USER-LOGIN', (data) => {
                this.userInfo = data;
            });
        },
        beforeDestroy() {
            EBUS.$off('EVENT-USER-LOGIN');
        },
        methods: {
            openUserModifyModal() {
                this.$refs.modifyModal.open(this.userInfo)
            },
            updateUserName(value) {
                this.userInfo.name = value;
            }
        }
    }
</script>

<style scoped>
    .product-content {
        height: 100%;
        padding: 8px;
    }
    .other-content {
        height: 100%;
        padding: 8px;
    }
</style>
