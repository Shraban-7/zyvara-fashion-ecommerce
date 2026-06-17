
<section class="cat-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-sub">Explore our curated collections</p>
            </div>

            <a href="{{ route('products.index') }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="cat-grid">
            @foreach ($allMenuCategories as $category)
                <a href="{{ route('products.index', $category->slug) }}" class="cat-item">
                    <div class="cat-card">

                        <img
                            src="{{ set_image($category->image) }}"
                            alt="{{ $category->name }}"
                            class="cat-img">

                        <div class="cat-overlay"></div>

                        <div class="cat-content">

                            <div>
                                
                            </div>

                            <div class="cat-bottom">
                                <h3 class="cat-name">
                                    {{ $category->name }}
                                </h3>

                                <span class="cat-btn">
                                    Explore
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>

                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
.cat-section{
    padding:60px 0;
    background:#fafafa;
}

.home-wrap{
    max-width:1320px;
    margin:auto;
    padding:0 16px;
}

/* Header */

.section-head{
    display:flex;
    justify-content:space-between;
    align-items:end;
    margin-bottom:32px;
}

.section-title{
    font-size:32px;
    font-weight:800;
    margin:0;
    color:#111827;
}

.section-sub{
    color:#6b7280;
    margin-top:8px;
}

.section-link{
    display:flex;
    align-items:center;
    gap:8px;
    padding:12px 18px;
    border-radius:14px;
    background:#fff;
    color:#111827;
    font-weight:600;
    border:1px solid #e5e7eb;
    transition:.3s;
}

.section-link:hover{
    background:#111827;
    color:#fff;
}

/* Grid */

.cat-grid{
    display:grid;
    gap:20px;
    grid-template-columns:repeat(2,1fr);
}

@media(min-width:768px){
    .cat-grid{
        grid-template-columns:repeat(3,1fr);
    }
}

@media(min-width:1200px){
    .cat-grid{
        grid-template-columns:repeat(5,1fr);
    }
}

/* Card */

.cat-card{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    height:340px;
    background:#eee;
    transition:.4s ease;
}

.cat-item:hover .cat-card{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(0,0,0,.15);
}

.cat-img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform .7s ease;
}

.cat-item:hover .cat-img{
    transform:scale(1.12);
}

.cat-overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(
        180deg,
        rgba(0,0,0,.05),
        rgba(0,0,0,.75)
    );
}

.cat-content{
    position:absolute;
    inset:0;
    padding:18px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    z-index:2;
}

.cat-badge{
    width:max-content;
    padding:8px 12px;
    border-radius:999px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(12px);
    color:#fff;
    font-size:12px;
    font-weight:600;
}

.cat-bottom{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.cat-name{
    color:#fff;
    font-size:22px;
    font-weight:800;
    margin:0;
    line-height:1.2;
}

.cat-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    width:max-content;
    padding:10px 14px;
    border-radius:12px;
    background:#fff;
    color:#111827;
    font-size:13px;
    font-weight:700;
    transition:.3s;
}

.cat-item:hover .cat-btn{
    transform:translateX(4px);
}

@media(max-width:640px){

    .cat-card{
        height:240px;
        border-radius:18px;
    }

    .cat-name{
        font-size:18px;
    }

    .section-title{
        font-size:24px;
    }

    .section-head{
        flex-direction:column;
        align-items:flex-start;
        gap:16px;
    }
}
</style>

