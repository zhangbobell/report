<div class="pct100"><!-- 标签将在footer中关闭，请勿提前关闭，以免造成错误。 -->
    <div class="sidebar pct20 l wh tc bg39 ">
	<div class="lh48 menu-title">菜 单</div>
	<ul class="tl ml10 lstn">
            <li class=""><a href="<?php echo base_url().'admin/' ?>">第一时间</a></li>
		<li class=" ml10">授权渠道</li>
                    <li class=" ml10"><a href="channel_auth/channel_scale/">渠道规模</a></li>
                    <li class=" ml10"><a href="channel_auth/channel_quality/">渠道质量</a></li>
                    <li class=" ml10"><a href="channel_auth/trend_analysis/">趋势分析</a></li>
                <li class=" ml10">非授权渠道</li>
                    <li class=" ml10"><a href="channel_noauth/trend_analysis/">趋势分析</a></li>
                    <li class=" ml10"><a href="channel_noauth/rank_noauth/">非授权商家名单</a></li>
                <li class=" ml10">业务分析</li>
                    <li class=" ml20">招 商</li>
                        <li class=" ml20"><a href="recruit/recruit_effect">招商效果查询*</a></li>
                        <li class=" ml20"><a href="recruit/list_analysis">名单分析*</a></li>
                        <li class=" ml20"><a href="recruit/recruiter_analysis">招商人员分析*</a></li>
                     <li class=" ml20">运 营</li>
                        <li class=" ml20"><a href="operation/operation_effect">运营效果查询*</a></li>
                        <li class=" ml20"><a href="operation/list_query">名单查询</a></li>
                        <li class=" ml20"><a href="operation/seller_search">搜索*</a></li>
                <li class=" ml10">榜单</li>
                    <li class=" ml10"><a href="rank_list/sales_rate_rank/">分销商销量增长率排行</a></li>
                    <li class=" ml10"><a href="rank_list/sales_rank/">分销商销量排行</a></li>
                    <li class=" ml10"><a href="rank_list/product_sales_rate_rank/">产品销量增长率排行</a></li>
                    <li class=" ml10"><a href="rank_list/product_sales_rank/">产品销量排行</a></li>
                <li class=" ml10">KPI查询*</li>
                    <li class=" ml10"><a href="kpi/kpi_weekly">周度KPI及达成进度*</a></li>
                    <li class=" ml10"><a href="kpi/kpi_monthly">月度KPI及达成进度*</a></li>
                    <li class=" ml10"><a href="kpi/kpi_yearly">年度KPI及达成进度*</a></li>
                <?php if($this->session->userdata['groupID'] === '0'): ?>
                <li class=" ml10">系统权限管理</li>   
                    <li class=" ml10"><a href="competence_management/project_management/">项目管理</a></li>
                    <li class=" ml10"><a href="competence_management/user_management/">用户管理</a></li>
                <?php endif; ?>
                    <li class=" ml10"><a href="upload/index">旺旺记录上传</a></li>
                    <li class=" ml10"><a href="tag/addTag">项目标签管理</a></li>
                    <li class=" ml10"><a href="smart/smartHome">Smart</a></li>
                <li class=" ml10"><a href="feedback/index">意见反馈</a></li>
	</ul>
</div>
    <div class="main-part"><!-- 标签将在footer中关闭，请勿提前关闭，以免造成错误。 -->
        <div class="main-part-wrap"><!-- 标签将在footer中关闭，请勿提前关闭，以免造成错误。 -->

