<template>
    <div class="no-padding">
        <Card :style="{marginTop: '5px', overflowX: 'hidden', padding: 0}">
            <p slot="title">
                <Icon type="logo-freebsd-devil" />
                大事记
            </p>
            <Timeline :style="{maxHeight: '300px', overflowY: 'scroll', position: 'relative', right: '-25px'}">
                <TimelineItem>
                    <Icon type="ios-leaf-outline" size="15" slot="dot" />
                    <p><b>2021年5月24</b></p>
                    <p style="font-size: 13px;">修改Redis链接地址过长的展示</p>
                </TimelineItem>
                <TimelineItem>
                    <Icon type="ios-ribbon" size="15" slot="dot"/>
                    <p><b>2020年6月4</b></p>
                    <p style="font-size: 13px;">页面布局完成，增加修改信息功能</p>
                </TimelineItem>
                <TimelineItem>
                    <Icon type="ios-log-in" size="15" slot="dot" />
                    <p><b>2020年5月25</b></p>
                    <p style="font-size: 13px;">框架改造，集成view-design,完成JWT登录</p>
                </TimelineItem>
                <TimelineItem>
                    <Icon type="ios-leaf-outline" size="15" slot="dot" />
                    <p><b>2020年5月15</b></p>
                    <p style="font-size: 13px;">选择框架，正式启动项目</p>
                </TimelineItem>
            </Timeline>
        </Card>
    </div>
</template>

<script>
    export default {
        name: "Memorabilia"
    }
</script>

<style>
    .no-padding .ivu-card-body {
        padding: 8px 0 5px 0;
    }
</style>
