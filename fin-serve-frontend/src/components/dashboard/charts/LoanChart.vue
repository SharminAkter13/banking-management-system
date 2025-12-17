<template>
  <div class="rounded-xl bg-white p-4 shadow">
    <h3 class="mb-4 text-lg font-semibold">Loans Per Month</h3>
    <ApexChart
      type="line"
      height="300"
      :series="series"
      :options="options"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import ApexChart from 'vue3-apexcharts'

const props = defineProps({ data: Array })

const series = computed(() => [{
  name: 'Loans',
  data: props.data || []
}])

const options = {
  xaxis: {
    categories: [
      'Jan','Feb','Mar','Apr','May','Jun',
      'Jul','Aug','Sep','Oct','Nov','Dec'
    ]
  }
}
</script>
