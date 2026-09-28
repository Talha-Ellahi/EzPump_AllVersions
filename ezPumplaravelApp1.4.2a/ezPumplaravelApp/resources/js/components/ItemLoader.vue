<script setup>
import {ref, reactive, onMounted, useSlots, onBeforeUnmount, watchEffect} from "vue";
// import ImageInput from "../Components/ImageInput.vue";

const props = defineProps({
	// modelValue: Number,
	// label: String,
	apiEndpoint: String,
	title: String,
	extraArgs: String,

});
const search = () => {

pageData.pageNo = 1;
pageData.items = [];
getPageData();
}
watchEffect(() => {
  console.log('extraArgs in ItemLoader:', props.extraArgs);
 try{
	search();

 } catch (error) {
 }
});

const slots = useSlots();
const apiMeta = ref({count: null, next: null});
const loading = ref(false);
const pageData = reactive({
	rect: null,
	user: null,
	items: [],
	pageNo: 1,
	loading: false,
	searchTerm: "",
	});
const response = ref(null);
const getPageData = async () => {
	loading.value = true;
	console.log(props)
	// Assuming you have a correct API endpoint
	await fetch(`${props.apiEndpoint}?page=${pageData.pageNo}&search=${pageData.searchTerm}&${props.extraArgs ?? ''}`)
		.then((response) => response.json())
		.then((results) => {
			console.log(results);
			pageData.items = pageData.items.concat(results);
			response.value = results;
			delete results.items;
			apiMeta.value = results;
			loading.value = false;
		})
		.catch((error) => {
			console.error('Error fetching data:', error);
			loading.value = false;
		});

};

const loadMoreItems = () => {
	// Call your API or method to load more items
	// Update items.value with the new data
	if (apiMeta.value.next) {
		pageData.pageNo += 1;
		getPageData();
		console.log("LoadMoreItems");

	} else {

	}
};
defineExpose({
  search,
});
const infiniteScrollTrigger = ref();
// Add scroll event listener
const handleScroll = () => {
	const trigger = infiniteScrollTrigger.value;
	if (trigger) {
		// debugger;
		pageData.rect = trigger.getBoundingClientRect();

		const isInView = (
			pageData.rect.top >= 0 &&
			pageData.rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
		);

		if (isInView) {
			loadMoreItems();
		} else {
			console.log("not in view");
		}

	} else {
		console.log("trigger not visible");
	}
};
onMounted(async () => {
	window.addEventListener('scroll', handleScroll);
	await getPageData();
});
onBeforeUnmount(() => {
	window.removeEventListener('scroll', handleScroll);
});

</script>

<template>
	<div class="container mt-5">
	<h3 class="text-center">{{props.title}}</h3>

		<!--<pre style="height: 30em; overflow-y: scroll">{{ pageData }}</pre>-->
		<div id="actions" class="d-flex justify-content-between align-items-center">
			<div class="">

			<label>Search:
				<input v-model="pageData.searchTerm" aria-controls="example" class="form-control form-control-sm" placeholder="Search..." type="search" @keydown.enter="search">
			</label>
			<button class="btn btn-success ms-1" type="button" @click="search">Search Item</button>
			</div>
				<div class="d-flex"> 
			<slot  :items="pageData.items" :refresh="search"   name="actionButtons"></slot>

				</div>
		</div>
		<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">

			<!-- Card 1 -->

			<div v-for="item in pageData.items" class="col-sm-12 col-md-6 col-lg-4">
				<div key={item.id}>
					<slot :item="item" name="item-detail"></slot>
				</div>
			
		
			</div>


		</div>
		<div class="flex justify-center items-center mt-8 mb-8">
			<h2 v-if="apiMeta.count===0 && (!apiMeta.next)">No Data Found</h2>
			<h2 v-else-if="apiMeta.count!=null && (!apiMeta.next)">No More Items</h2>
			<div v-else-if="!loading" ref="infiniteScrollTrigger" class="infinite-scroll-trigger"></div>
			<div v-else class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
	</div>
	<div class="mt-4"></div>
</template>

<style scoped>

</style>