<template>
  <div class="searchbar">
    <div class="d-flex">
      <v-select
        placeholder="Enter signs and symptoms to search for herbs"
        multiple
        :options="options"
        :value="selectedSigns"
        @input="signsUpdated"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
      <v-select
        placeholder="Results type"
        :value="type"
        :options="['Herb Formula', 'Herb']"
        @input="typeUpdated"
        class="typeinput"
        :clearable="false"
      ></v-select>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.searchbar {
  padding: 5px 0;
}
.searchinput {
  flex: 1;
  margin-right: 10px;
}
.typeinput {
  width: 200px;
}
</style>
<script>
export default {
  props: ["type", "options", "selectedSigns"],
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    signsUpdated(signs) {
      this.$emit("signsUpdated", signs);
    },
    typeUpdated(type) {
      this.$emit("typeUpdated", type);
    },
    getOptionLabel(option) {
      return option.value;
    },
    getOptionKey(option) {
      return option.id;
    }
  }
};
</script>
