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
        <p class="mt-6">For general inquiries, please reach out to (207) 859-4800 or <a href="mailto:admissions@colby.edu" class="underline hover:no-underline cursor-pointer" style="color: #002878;">admissions@colby.edu</a>. For financial aid inquiries, please reach out to (207) 859-4830 or <a href="mailto:finaid@colby.edu" class="underline hover:no-underline cursor-pointer" style="color: #002878;">finaid@colby.edu</a>. To connect with your admissions counselor, use the search bar available on this page.</p>
	    <p class="mt-2">To connect with a current student, please <a href="https://admissions.colby.edu/register/connect" class="underline hover:no-underline cursor-pointer" style="color: #002878;">click here</a>. </p>
    </div>

    <main class="colby-counselors-main main container mx-auto mt-8 md:mt-8 lg:mt-16" id="main" x-data="{
                tab: 'us',
                territory: '',
                counselors: [],
                filteredCounselors: [],
                loading: true,
                loaded: false,
                async init() {
                    // set map click event listener
                    window.addEventListener('regionUpdated', (event) => {
                        this.territory = event.detail;
                        this.fetchCounselors();
                    });

                    // process url params
                    const currentUrl = new URL(window.location.href);
                    const tabParam = currentUrl.searchParams.get('tab');
                    const territoryParam = currentUrl.searchParams.get('territory');
                    
                    if (tabParam) {
                        this.tab = tabParam;
                        window.activeTab = tabParam;
                    } else {
                        window.activeTab = 'us';
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('tab', 'us');
                        currentUrl.searchParams.delete('territory');
                        window.history.pushState({}, '', currentUrl.toString());
                        
                    }
                    window.dispatchEvent(setActiveMap);

                    if (territoryParam) {
                        this.territory = territoryParam;    
                        const eventZoom = new CustomEvent('mapZoom', {
                            detail: territoryParam,
                        });
                        window.dispatchEvent(eventZoom);
                    }
                    const counselors = await this.fetchCounselors();
                    this.loaded = true;
                    
                },

                setTab(tab) {

                    this.tab = tab;
                    this.territory = '';
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('tab', tab);
                    currentUrl.searchParams.delete('territory');
	                window.history.pushState({}, '', currentUrl.toString());
                    window.activeTab = tab;
                    this.filteredCounselors = this.counselors;
                    window.dispatchEvent(setActiveMap);
                    setTimeout(() => {
                        window.dispatchEvent(eventRefresh);
                    }, 100);
                    
                },

                async fetchCounselors() {
                    try {
                        const response = await fetch('https://dev-54ta5gq-vzguznjotqfs2.us-2.platformsh.site/wp-json/counselors/v1/counselors');
                        const data = await response.json();
                        this.counselors = data;
                        this.filteredCounselors = this.filterCounselors(data, this.territory); // Initial filtering
                    } catch (error) {
                        console.error('Error fetching counselors:', error);
                    } finally {
                        const setMapDescriptions = new CustomEvent('setMapDescriptions', {
                            detail: {
                                counselors: this.counselors,
                            }
                        });
                        if(this.loading) {
                            window.dispatchEvent(setMapDescriptions);
                        }
                   
                        this.loading = false;
                    }
                },

                filterCounselors() {
                    if (!this.territory) {
                        return this.counselors;
                    } else {
                     const filteredCounselorList = this.counselors.filter((counselor) => {
                            if (counselor.terms && Array.isArray(counselor.terms.territories)) {
                                return counselor.terms.territories.some(territory => {

                                    if (territory.parent && territory.parent.slug === this.territory) {
                                        return true;
                                    }

                                    if (territory.slug === this.territory) {
                                        return true;
                                    }

                                    return false;
                                });
                            }
                        });
                        
                        return filteredCounselorList;
                    }
                },

                transformTerritoryName() {
                    if (!this.territory) return 'All Counselors'; // Fallback for empty string


                    // Special case for 'mid-atlantic'
                    if (this.territory.toLowerCase() === 'mid-atlantic') {
                        return 'Mid-Atlantic';
                    }

                    // Replace dashes with spaces, capitalize the first letter of each word
                    return this.territory
                        .split('-')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            }"
            >

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
                            @click="setTab('us')" 
                            :class="{
                                'counselor-bg-colbyBlue text-white': tab === 'us',
                                'counselor-text-colbyBlue': tab !== 'us'
                            }" 
                            class="mb-4 toggle-button counselor-mr-8 counselor-py-2 counselor-px-12 counselor-border counselor-border-gray-500 counselor-rounded counselor-font-medium">
                            Domestic
                        </button>
                        
                        <button 
                            @click="setTab('international')"  
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
            <h3 class="inline-block px-2 counselor-font-bold py-2 text-white text-2xl" style="background-color: #022168;" x-text="transformTerritoryName()">
            </h3>
        </div>

        <div>
            <div
            class="counselor-list">

                <div x-show="loading" class="loading">Loading counselors...</div>

                <template x-if="filteredCounselors.length > 0">
                    <div class="counselor-grid counselor-grid-cols-1 md:counselor-grid-cols-2">
                        <template x-for="counselor in filteredCounselors" :key="counselor.id">
                            <template x-if="!counselor.meta.highlight || (!counselor.meta.highlight[0])">
                            <article class="px-container py-8 counselor-grid md:counselor-grid-cols-1 lg:counselor-grid-cols-2">
                                <div class="counselor-mb-2 lg:counselor-mb-0">
                                    <img :src="counselor.thumbnail" alt="Counselor photo" class="counselor-photo" />
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
