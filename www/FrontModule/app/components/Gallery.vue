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
            <template v-if="component.multiple">
                gallery
            </template>
            <template v-else>
                <template v-if="component.images.length > 0">
                    <div class="composer-gallery" v-for="(image, index) in component.images" :key="image.index">
                        <div class="composer-gallery-item">
                            <button type="button" v-on:click="handleRemoveImage(index)" class="delete">Remove</button>
                            <img :src="image.fileUrl" alt="" class="img-fluid">
                        </div>
                    </div>
                </template>
                <template v-else>
                    <input class="input gallery-input" :id="'input-'+component.id" type="file" v-on:change="handleImages($event)">
                    <label :for="'input-'+component.id" class="gallery-label">
                        <div class="heading"><svg class="icon"><use xlink:href="#image"></use></svg>Image</div>
                        <p class="mb-0">Drag the file here or click and select in file browser</p>
                    </label>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            component: Object
        },
        name: "Gallery",
        methods: {
            async getBase64(file) {
                const reader = new FileReader()
                reader.readAsDataURL(file)

                return new Promise((resolve, reject) => {
                    reader.onload = () => resolve(reader.result)
                    reader.onerror = error => reject(error)
                })
            },
            async handleImages(e) {
                for(let i = 0; i < e.target.files.length; i++) {
                    let base64 = await this.getBase64(e.target.files[i])

                    this.component.images.push({
                        fileUrl: URL.createObjectURL(e.target.files[i]),
                        file: e.target.files[i],
                        base64: base64
                    })
                }

                console.log(this.component.images)

                const config = {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                }

                const url = '/api/image/save'

                axios.post(url, this.component.images, config)
                    .then((result) => {
                        this.component.images[0].fileUrl = result.data.image[0]
                    })
                    .catch((err) => {
                        console.log(err)
                        this.$toasted.error('There was an error while saving the record')
                    })
            },
            handleRemoveImage(index) {
                this.component.images = this.component.images.filter(item => { return this.component.images.indexOf(item) !== index });
            },
            handleRemove(id) {
                if(this.component.required) {
                    this.$toasted.error('Cannot remove a required field')
                }
                else {
                    this.$store.commit('removeComposerItem', id)
                }
            },
            moveItemTop(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemTop', item, 1)
                }
            },
            moveItemDown(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemDown', item, 1)
                }
            }
        }
    };
</script>