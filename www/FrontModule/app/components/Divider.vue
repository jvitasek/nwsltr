<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="handleRemove(component.id)">
                    <svg class="icon">
                        <title v-translate>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)">
                    <svg class="icon">
                        <title v-translate>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)">
                    <svg class="icon">
                        <title v-translate>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
            </div>
            <div class="divider"></div> 
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            component: Object
        },
        name: "Divider",
        methods: {
            handleRemove(id) {
                if(this.component.required) {
                    this.$toasted.error(vm.$gettext('Cannot remove a required field'))
                }
                else {
                    this.$store.commit('removeComposerItem', id)
                }
            },
            moveItemTop(item) {
                if(this.component.required) {
                    this.$toasted.error(vm.$gettext('Cannot move a required field'))
                }
                else {
                    this.$store.commit('moveItemTop', item, 1)
                }
            },
            moveItemDown(item) {
                if(this.component.required) {
                    this.$toasted.error(vm.$gettext('Cannot move a required field'))
                }
                else {
                    this.$store.commit('moveItemDown', item, 1)
                }
            }
        },
        mounted() {
            
        }
    };
</script>