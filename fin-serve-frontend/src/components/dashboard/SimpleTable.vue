<template>
  <div>
    <table v-if="rows.length" class="w-full text-sm">
      <thead>
        <tr class="border-b">
          <th v-for="(v, k) in rows[0]" :key="k" class="py-2 text-left">
            {{ k }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, i) in rows" :key="i" class="border-b">
          <td v-for="(v, k) in row" :key="k" class="py-2">
            {{ v }}
          </td>
        </tr>
      </tbody>
    </table>

    <p v-else class="text-center py-4 text-gray-500">
      No data available.
    </p>
  </div>
</template>

<script setup>
defineProps({
  rows: {
    type: Array,
    default: () => [] // default to empty array
  }
})
</script>
