<template>
  <div class="searchbar">
    <div class="d-flex">
      <v-select
        :placeholder="`Select terms to search for ${type.toLowerCase()}`"
        multiple
        :options="options"
        :value="selectedOptions"
        @input="$emit('optionsUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
        v-if="!nameSearch"
      ></v-select>
      <input
        v-if="nameSearch"
        class="form-control namesearchinput searchinput"
        @input="$emit('nameUpdated', $event.target.value)"
        :placeholder="`Enter a name to search for ${type.toLowerCase()}`"
        :value="nameToSearch"
      />
      <v-select
        placeholder="Results type"
        :value="type"
        :options="['Herb Formula', 'Herb']"
        @input="$emit('typeUpdated', $event)"
        class="typeinput"
        :clearable="false"
      ></v-select>
    </div>
    <a
      href="javascript:void(0)"
      @click="$emit('nameSearchToggled')"
      class="name-search"
    >{{ !nameSearch ? `Search for a specific ${type.toLowerCase()}` : 'Search by terms' }}</a>
  </div>
</template>
<style lang="scss" scoped>
.advanced-search,
.name-search {
  padding: 5px;
  display: inline-block;
}
.searchbar {
  padding: 5px 0;
}
.searchinput {
  flex: 1;
  margin-right: 10px;
}
.searchinput:first-child {
  margin-right: 10px !important;
}
.searchinput:last-child {
  margin-right: 0;
}
.namesearchinput {
  height: 32px;
}
.namesearchinput::-webkit-input-placeholder {
  color: #000;
}

.namesearchinput:-ms-input-placeholder {
  color: #000;
}

.namesearchinput::placeholder {
  color: #000;
}
.typeinput {
  width: 200px;
}
</style>
<script>
export default {
  props: ["type", "options", "selectedOptions", "nameSearch", "nameToSearch"],
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    getOptionLabel(option) {
      return option.value;
    },
    getOptionKey(option) {
      return option.id;
    }
  }
};
</script>
