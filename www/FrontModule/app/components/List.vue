<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="handleRemove(component.id)">
                    <svg class="icon">
                        <title>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)">
                    <svg class="icon">
                        <title>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)">
                    <svg class="icon">
                        <title>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
            </div>
            <component :is="component.listType" v-if="renderComponent">
                <li contenteditable="true"
                    v-for="(item, index) in component.items"
                    :key="index"
                    @keydown="onKeydown($event, index)"
                    @input="onInput($event, index, item)"
                    class="input list"
                    :id="'input-item-'+component.id+'-'+index"
                >{{ item }}</li>
            </component>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                renderComponent: true
            }
        },
        props: {
            component: Object
        },
        name: "List",
        methods: {
            handleRemove(id) {
                this.$store.commit('removeComposerItem', id)
            },
            moveItemTop(item) {
                this.$store.commit('moveItemTop', item, 1)
            },
            moveItemDown(item) {
                this.$store.commit('moveItemDown', item, 1)
            },
            onKeydown(e, index) {
                if(e.key === 'Enter') {
                    e.preventDefault()
                    this.renderComponent = false
                    let componentId = this.component.id
                    this.$store.commit('addListItem', {componentId, index})
                    this.$nextTick(() => {
                        this.renderComponent = true;
                    });
                    let that = this
                    setTimeout(function(){ 
                        let input = document.querySelector('#input-item-' + that.component.id + '-' + (index+1))
                        input.focus()
                        input.innerHTML = ''
                    }, 50);
                }
                else if(e.key === 'Backspace') {
                    if(e.target.innerHTML.length === 0 && this.component.items.length > 1) {
                        e.preventDefault()
                        this.renderComponent = false
                        let componentId = this.component.id
                        this.$store.commit('removeListItem', {componentId, index})
                        this.$nextTick(() => {
                            this.renderComponent = true;
                        });
                        // let that = this
                        // setTimeout(function(){ 
                        //     document.querySelector('#input-item-' + that.component.id + '-' + String(parseInt(that.component.items.length)-1)).focus()
                        // }, 50);
                    }
                }
            },
            onInput(e, index, item) {
                this.component.items[index] = e.target.innerText
            }
        },
        mounted() {
            if(this.component.items.length <= 0) {
                document.querySelector('#input-item-' + this.component.id + '-' + 0).innerHTML = ''
            }
        }
    };
</script>