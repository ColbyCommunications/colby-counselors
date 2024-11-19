<?php
/**
 * Counselors_Post_Type post type archive
 *
 * @package Colby/Counselors
 */

get_header();

if ( have_posts() ) :

    ?>
    <div style="background-color: #022168;">
        <h1 class="mx-auto px-container container mb-4 py-2 text-3xl counselor-font-bold" style=" color: #FFFF"><?php colby_counselors_archive_title(); ?></h1>
    </div>

    <main class="colby-counselors-main main container mx-auto mt-8 md:mt-8 lg:mt-16" id="main" x-data="{
                tab: 'us',
                counselors: [],
                filteredCounselors: [],
                loading: true,
                displayText: 'All Counselors',
                region: '',
                init() {
                    window.addEventListener('regionUpdated', (event) => {
                        this.region = event.detail;
                        console.log(`Region updated from external JS: ${this.region}`);
                        this.fetchCounselors();
                        this.displayText = this.region;
                    });
                },

                async fetchCounselors() {
                console.log('1');
                    try {
                        const response = await fetch('https://colby-admissions-production.lndo.site/wp-json/counselors/v1/counselors');
                        const data = await response.json();
                        console.log('Counselors data:', data); // Log the data to check
                        this.counselors = data;
                        console.log(this.filteredCounselors);
                        this.filteredCounselors = this.filterCounselors(data, this.region); // Initial filtering
                        console.log(`filtered counselors: ${this.filteredCounselors}`);
                    } catch (error) {
                        console.error('Error fetching counselors:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                filterCounselors(counselors, region) {
                    console.log(`counselors: ${counselors}`);
                    console.log(`region: ${region}`);

                    if (!region) {
                        console.log('No region selected');
                        return counselors;
                    } else {
                        console.log(`You selected ${region}`);
                        return counselors.filter((counselor) => {
                            if (counselor.terms && Array.isArray(counselor.terms.territories)) {
                                return counselor.terms.territories.some(territory => {
                                    return territory.parent && territory.parent.slug === region;   
                                })
                            }
                        });
                    }
                }
            }"
            x-init="fetchCounselors()">

        <p class="px-container">For general inquiries, please reach out to (207) 859-4800 or <a href="mailto:admissions@colby.edu" style="color: #062da1">admissions@colby.edu.</a> For financial aid inquiries, please reach out
            to (207) 859-4830 or <a href="mailto:finaid@colby.edu" style="color: #062da1">finaid@colby.edu</a>. </p>
        <div id="colby-counselors-territory-picker"></div>

        <?php include_once 'highlight.php'; ?>

        <?php if ( is_archive() ) : ?>
            <div class="mb-12 px-container">
                <h2 class="mb-6 text-2xl font-bold" style="color: #052168;">Contacts by Region</h2>
                <div>
                    <div class="mb-6">
                        <button 
                            @click="tab = 'us'" 
                            :class="{
                                'counselor-bg-colbyBlue text-white': tab === 'us',
                                'counselor-text-colbyBlue': tab !== 'us'
                            }" 
                            class="toggle-button counselor-mr-8 counselor-py-2 counselor-px-12 counselor-border counselor-border-gray-500 counselor-rounded counselor-font-medium">
                            Domestic
                        </button>
                        
                        <button 
                            @click="tab = 'international'" 
                            :class="{
                                'counselor-bg-colbyBlue text-white': tab === 'international',
                                'counselor-text-colbyBlue': tab !== 'international'
                            }" 
                            class="toggle-button counselor-py-2 counselor-px-12 counselor-border counselor-border-gray-500 counselor-rounded counselor-font-medium">
                            International
                        </button>
                    </div>

                    <div class="content">
                        <div class="counselor-flex counselor-justify-center counselor-items-center">
                            <div x-show="tab === 'us'" id="map1"></div>
                            <div x-show="tab === 'international'" id="map2"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="px-container text-left">
            <h3 class="inline-block px-2 counselor-font-bold py-2 text-white text-2xl" style="background-color: #022168;" x-text="displayText">
            </h3>
        </div>

        <div class="counselor-grid counselor-grid-cols-1 md:counselor-grid-cols-2">
            <div
            class="counselor-list">

                <div x-show="loading" class="loading">Loading counselors...</div>

                <template x-if="filteredCounselors.length > 0">
                    <div>
                        <template x-for="counselor in filteredCounselors" :key="counselor.id">
                            <article class="px-container py-8 counselor-grid md:counselor-grid-cols-1 lg:counselor-grid-cols-2">
                                <div class="counselor-mb-2 lg:counselor-mb-0">
                                    <img :src="counselor.meta.photo" alt="Counselor photo" class="counselor-photo" />
                                    
                                    <div class="lg:counselor-h-full counselor-h-72 counselor-w-72 md:counselor-w-auto counselor-block  counselor-flex counselor-justify-center counselor-items-center" style="background-color: #e4e8ef;">
                                    </div>
                                </div>
                                <div class="lg:counselor-pl-2 md:text-md">
                                    <h4 class="counselor-font-bold counselor-text-lg" x-text="`${counselor.meta.first_name} ${counselor.meta.last_name}`"></h4>
                                    <p class="mb-4 font-normal" x-text="counselor.meta.pronouns"></p>
                                    <p style="color: #273057" x-text="counselor.meta.job_title"></p>
                                    <div>
                                        <p><a style="color: #062da1" x-text="counselor.meta.email"></a></p>
                                        <p x-text="counselor.meta.phone"></p>
                                    </div>
                                    <div class="">
                                        <p class="counselor-font-bold">Primary Contact:
                                            <span class="font-normal" 
                                                x-show="counselor.terms && counselor.terms.territories && counselor.terms.territories.length > 0" 
                                                x-text="counselor.terms.territories 
                                                        ? counselor.terms.territories.filter(territory => 
                                                                territory.grandparent && 
                                                                territory.grandparent.slug === tab
                                                            ).map(territory => territory.name).join(', ') 
                                                        : ''">
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </template>
                    </div>
                </template>
                <!-- No Results Found -->
                <div x-show="!loading && filteredCounselors.length === 0">
                    No counselors found.
                </div>
            </div>
        </div>
    </main>
 <?php
endif;
get_footer();
