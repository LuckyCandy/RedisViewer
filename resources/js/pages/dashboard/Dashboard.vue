<template>
    <div>
        <Row :style="contentStyle">
            <Col span="13" offset="2" :style="{minWidth: '760px', padding: '5px 5px 0 0'}">
                <Card :bordered="false" :padding="2" :style="{margin: '0 5px 5px 5px', float: 'left', width: '240px', height: '120px'}" v-for="(product, index) in products" :key="index" :to="{path: 'redis'}">
                    <p slot="title" style="text-align: center; font-size: 17px; font-family: monospace">
                        {{ product.title }}
                    </p>
                    <div>
                        <img :src="product.icon" style="float: left; border-right: 2px dotted #e8eaec"/>
                        <p style="height: 50px; padding: 10px 2px 8px 8px; overflow: hidden; color: #59565a">
                            {{ product.desc }}
                        </p>
                    </div>
                </Card>
            </Col>
            <Col span="7" :style="{minWidth: '300px', padding: '5px 0 10px 0'}">
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
            </Col>
        </Row>
        <ModifyUserInfo ref="modifyModal" @refreshUserName="updateUserName"></ModifyUserInfo>
    </div>
</template>

<script>
    import {getInfo} from "../../api/user";
    import ModifyUserInfo from './components/ModifyUserInfo';
    import Memorabilia from './components/Memorabilia';

    export default {
        name: 'Dashboard',
        components: {ModifyUserInfo, Memorabilia},
        data() {
            return {
                isWindowContinueChange: false,
                contentStyle: {
                    height: '',
                    display: 'flex',
                    minHeight: '500px',
                    minWidth: '900px',
                    overflow: 'hidden',
                    background: 'rgb(0, 0, 0, 0.2)'
                },
                userInfo: {},
                products: [
                    {
                        desc: 'Redis数据库查询工具，告别枯燥的命令行！',
                        title: 'Redis Manager',
                        to: {name: 'redis-index'},
                        icon: '/images/product-redis.png'
                    },
                    {
                        desc: '自动化Api测试工具，接口调试就是这么简单',
                        title: 'Auto Test',
                        to: {name: 'auto-test-index'},
                        icon: '/images/product-worker.png'
                    }
                ]
            }
        },
        beforeCreate() {
            EBUS.$on('EVENT-USER-LOGIN', (info) => {
                this.userInfo = info;
            });
        },
        beforeDestroy() {
            EBUS.$off('EVENT-USER-LOGIN')
        },
        created() {
            this.computeContentHeight();
            window.onresize = () => {
                return (() => {
                    if (!this.isWindowContinueChange) {
                        this.isWindowContinueChange = true;
                        setTimeout(() => {
                            this.isWindowContinueChange = false;
                            this.computeContentHeight();
                        }, 500);
                    }
                })();
            };
            this.getUserInfo();
        },
        methods: {
            getUserInfo() {
                getInfo().then(response => {
                    this.userInfo = response.data;
                }).catch(error => {});
            },
            openUserModifyModal() {
                this.$refs.modifyModal.open(this.userInfo)
            },
            updateUserName(value) {
                this.userInfo.name = value;
            },
            computeContentHeight() {
                this.contentStyle.height = window.innerHeight - 64 + 'px';
            },
        }
    }
</script>
