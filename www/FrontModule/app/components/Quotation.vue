<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="setBold(component.id)">
                    <svg class="icon icon--sm">
                        <title>Bold</title>
                        <use xlink:href="#bold"></use>
                    </svg>
                </button>
                <button v-on:click="setItalic(component.id)">
                    <svg class="icon icon--sm">
                        <title>Italic</title>
                        <use xlink:href="#italic"></use>
                    </svg>
                </button>
                <button v-on:click="setUnderline(component.id)">
                    <svg class="icon icon--sm">
                        <title>Underline</title>
                        <use xlink:href="#underline"></use>
                    </svg>
                </button>
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
            <div contenteditable="true"
                class="input quotation editable"
                :id="'input-'+component.id"
                @input="onInput"
            >
                {{ component.content }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            component: Object
        },
        name: "Quotation",
        methods: {
            onInput(e) {
                this.component.content = e.target.innerHTML
            },
            handleRemove(id) {
                this.$store.commit('removeComposerItem', id)
            },
            moveItemTop(item) {
                this.$store.commit('moveItemTop', item, 1)
            },
            moveItemDown(item) {
                this.$store.commit('moveItemDown', item, 1)
            },
            setBold() {
                document.execCommand('bold')
            },
            setItalic() {
                document.execCommand('italic')
            },
            setUnderline() {
                document.execCommand('underline')
            }
        },
        mounted() {
            document.querySelectorAll('.editable').forEach(function (item, index) {
                item.addEventListener('focus', () => {
                    item.innerHTML = item.innerHTML.replace('<p>', '').replace('</p>', ''); 
                });
            });
        }
    };
</script>