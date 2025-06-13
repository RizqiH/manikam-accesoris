// script.js
// Toggle hamburger menu
const navbarNav = document.querySelector(".navbar-nav");
document.querySelector("#hamburger-menu")?.addEventListener("click", () => {
  navbarNav.classList.toggle("active");
});

document.addEventListener("click", function (e) {
  const hamburger = document.querySelector("#hamburger-menu");
  if (!hamburger?.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }
});

const form = document.getElementById("ulasanForm");
const tabel = document.getElementById("hasilUlasan");

form.addEventListener("submit", function (e) {
  e.preventDefault();
  const nama = document.getElementById("nama").value;
  const pekerjaan = document.getElementById("pekerjaan").value;
  const pesan = document.getElementById("pesan").value;

  const barisBaru = document.createElement("tr");
  barisBaru.innerHTML = `
        <td>${nama}</td>
        <td>${pekerjaan}</td>
        <td>${pesan}</td>
      `;
  tabel.appendChild(barisBaru);
  form.reset();
});
document
  .getElementById("formPembelian")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const nama = document.getElementById("nama").value;
    const produk = document.getElementById("produk").value;
    const jumlah = document.getElementById("jumlah").value;

    const tabel = document
      .getElementById("tabelPembelian")
      .getElementsByTagName("tbody")[0];
    const row = tabel.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    const cell3 = row.insertCell(2);

    cell1.textContent = nama;
    cell2.textContent = produk;
    cell3.textContent = jumlah;

    document.getElementById("formPembelian").reset();
  });
