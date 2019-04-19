<template>
	<div class="home-index">
		<h3>最近登录用户</h3>
		<div class="home-index-bd">
			<el-table :data="info" border>
				<el-table-column prop="name" label="用户名" width="280"></el-table-column>
				<el-table-column prop="phone" label="联系电话" width="180"></el-table-column>
				<el-table-column prop="time" label="登录时间"></el-table-column>
			</el-table>
		</div>
	</div>
</template>
<script>
var vm = this;
export default {
	data() {
		return {
			info: [
				{
					name: '张三',
					phone: '12345678910',
					time: '2018-12-12 00:00:00'
				}
			]
		};
	},
	created() {
		let vm = this;
		vm.$store.commit('setProgressPage', 100);
		vm.$req(
			'post',
			{
				c: 'user',
				f: 'getActiveRows',
				a: 1
			},
			res => {
				vm.info = res.data;
			}
		);
		vm.$store.commit('setRowsCount', 0);
	},
	beforeDestory() {
		this.$store.commit('setProgressPage', 0);
	}
};
</script>
<style lang="less" scoped>
@import '../config';
.home-index {
	.el-table {
		margin-top: 20px;
		width: auto;
	}
}
</style>
