// ================== DATA PRODUK ==================
const products = [
  { name: "iPhone 15", desc: "Smartphone terbaru dari Apple.", price: 20000000, img: "iphone-15.webp", category: "Elektronik" },
  { name: "Samsung Galaxy S23", desc: "Smartphone flagship Samsung.", price: 18000000, img: "iphone-15.webp", category: "Elektronik" },
  
  { name: "T-Shirt Putih", desc: "Kaos nyaman 100% katun.", price: 150000, img: "iphone-15.webp", category: "Fashion" },
  { name: "Jaket Denim", desc: "Jaket jeans stylish.", price: 350000, img: "iphone-15.webp", category: "Fashion" },

  { name: "Blender Philips", desc: "Blender kuat untuk smoothie.", price: 500000, img: "iphone-15.webp", category: "Peralatan Rumah" },
  { name: "Rice Cooker", desc: "Penanak nasi serbaguna.", price: 400000, img: "iphone-15.webp", category: "Peralatan Rumah" },

  { name: "Novel Fiksi", desc: "Cerita seru penuh imajinasi.", price: 100000, img: "iphone-15.webp", category: "Buku" },
  { name: "Buku Sains", desc: "Ilmu pengetahuan modern.", price: 120000, img: "iphone-15.webp", category: "Buku" },

  { name: "Sepeda Gunung", desc: "Cocok untuk olahraga outdoor.", price: 2500000, img: "iphone-15.webp", category: "Olahraga" },
  { name: "Bola Sepak", desc: "Bola kulit standar FIFA.", price: 200000, img: "iphone-15.webp", category: "Olahraga" },
];

// ================== RENDER KATEGORI ==================
const filterCategory = document.getElementById("filterCategory");
const categories = [...new Set(products.map(p => p.category))];
categories.forEach(cat => {
  const option = document.createElement("option");
  option.value = cat;
  option.textContent = cat;
  filterCategory.appendChild(option);
});

// ================== RENDER PRODUK ==================
const productList = document.getElementById("productList");

function renderProducts(filteredProducts) {
  productList.innerHTML = "";
  if (filteredProducts.length === 0) {
    productList.innerHTML = `<p class="text-center">Produk tidak ditemukan.</p>`;
    return;
  }

  filteredProducts.forEach(p => {
    const col = document.createElement("div");
    col.className = "col-md-4 col-lg-3";
    col.innerHTML = `
      <div class="card h-100 shadow-sm">
        <img src="${p.img}" class="card-img-top" alt="${p.name}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${p.name}</h5>
          <p class="card-text small">${p.desc}</p>
          <p class="fw-bold text-primary">Rp ${p.price.toLocaleString("id-ID")}</p>
          <span class="badge bg-secondary mb-2">${p.category}</span>
          <button class="btn btn-sm btn-success mt-auto">Tambah ke Keranjang</button>
        </div>
      </div>
    `;
    productList.appendChild(col);
  });
}

renderProducts(products);

// ================== FILTER & SEARCH ==================
filterCategory.addEventListener("change", applyFilter);
document.getElementById("searchInput").addEventListener("input", applyFilter);

function applyFilter() {
  const selectedCategory = filterCategory.value;
  const searchQuery = document.getElementById("searchInput").value.toLowerCase();

  const filtered = products.filter(p => {
    const matchCategory = selectedCategory === "all" || p.category === selectedCategory;
    const matchSearch = p.name.toLowerCase().includes(searchQuery);
    return matchCategory && matchSearch;
  });

  renderProducts(filtered);
}