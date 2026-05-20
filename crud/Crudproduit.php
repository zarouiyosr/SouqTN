<?php
require '../config.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    header('Content-Type: application/json');
    $action = $_POST['action'];

    try {

        if ($action === 'create') {

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            $category_id = (int)($_POST['category_id'] ?? 0);

            if (!$name || !$description || !$price || !$stock || !$category_id) {
                echo json_encode(["status"=>"error","message"=>"Champs obligatoires"]);
                exit;
            }

            $image_path = null;

            if (!empty($_FILES['image']['name'])) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];

                if (!in_array($ext,$allowed)) {
                    throw new Exception("Format image invalide");
                }

                if (!is_dir("uploads")) mkdir("uploads",0777,true);

                $imgname = uniqid().".".$ext;
                move_uploaded_file($_FILES['image']['tmp_name'],"uploads/".$imgname);

                $image_path = "uploads/".$imgname;
            }

            $stmt = $pdo->prepare("
                INSERT INTO produits
                (name, description, price, image_url, stock, category_id, created_at)
                VALUES (?,?,?,?,?,?,NOW())
            ");

            $stmt->execute([$name,$description,$price,$image_path,$stock,$category_id]);

            echo json_encode(["status"=>"success","message"=>"Produit ajouté"]);
            exit;
        }

        if ($action === 'update_product') {

            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];

            $pdo->prepare("
                UPDATE produits
                SET name=?, description=?
                WHERE id=?
            ")->execute([$name,$description,$id]);

            echo json_encode(["status"=>"success","message"=>"Modifié"]);
            exit;
        }

        if ($action === 'delete_product') {

            $id = (int)$_POST['id'];

            $pdo->prepare("DELETE FROM produits WHERE id=?")->execute([$id]);

            echo json_encode(["status"=>"success","message"=>"Supprimé"]);
            exit;
        }

    } catch(Exception $e){
        echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
        exit;
    }
}

$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
$products = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Produits</title>

<style>
body{
  margin:0;
  font-family:'DM Sans',sans-serif;
  background:transparent;
}

/* ===== LAYOUT ===== */
.page{
  width:100%;
  display:grid;
  grid-template-columns:320px 1fr;
  gap:20px;
}

/* ===== CARD ===== */
.card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:16px;
  padding:20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.25);
}

/* ===== TITLES ===== */
h2{
  font-family:'Syne',sans-serif;
  font-size:1.1rem;
  margin-bottom:12px;
}

/* ===== INPUTS ===== */
input,select{
  width:100%;
  padding:10px;
  margin:8px 0;
  border-radius:10px;
  border:1px solid var(--border);
  background:var(--surface);
  color:var(--text);
  font-size:0.9rem;
}

input:focus,select:focus{
  border-color:var(--accent);
  outline:none;
}

/* ===== BUTTON ===== */
.btn{
  width:100%;
  padding:10px;
  border:none;
  border-radius:10px;
  background:var(--accent);
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:0.2s;
}

.btn:hover{
  background:#9A3010;
}

/* ===== TABLE ===== */
table{
  width:100%;
  border-collapse:collapse;
  margin-top:10px;
}

td{
  padding:12px;
  border-bottom:1px solid var(--border);
  font-size:0.85rem;
}

tr:hover td{
  background:rgba(255,255,255,0.03);
}

/* ===== ACTION BUTTONS ===== */
.edit{
  background:rgba(232, 160, 32, 0.15);
  color:#E8A020;
  border:none;
  padding:6px 10px;
  border-radius:8px;
  cursor:pointer;
  margin-right:6px;
}

.delete{
  background:rgba(239,68,68,0.15);
  color:#f87171;
  border:none;
  padding:6px 10px;
  border-radius:8px;
  cursor:pointer;
}

/* ===== MODAL ===== */
.modal{
  position:fixed;
  top:0;left:0;
  width:100%;height:100%;
  background:rgba(26, 10, 0, 0.6);
  display:flex;
  justify-content:center;
  align-items:center;
  opacity:0;
  pointer-events:none;
  transition:0.25s;
  z-index:200;
}

.modal.show{
  opacity:1;
  pointer-events:auto;
}

.modal-content{
  background:var(--card);
  border:1px solid var(--border);
  padding:20px;
  border-radius:16px;
  width:320px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
  .page{
    grid-template-columns:1fr;
  }
}
</style>
</head>

<body>

<div class="page">

<div class="card">

<h2>Créer produit</h2>

<form id="form" enctype="multipart/form-data">

<input name="name" placeholder="Nom">
<textarea name="description" placeholder="Description"></textarea>
<input type="number" step="0.001" name="price" placeholder="Prix TND">
<input type="number" name="stock" placeholder="Stock">

<select name="category_id">
<option value="">Catégorie</option>
<?php foreach($cats as $c): ?>
<option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
<?php endforeach; ?>
</select>

<input type="file" name="image">

<button class="btn">Créer</button>

</form>

</div>

<div class="card">

<h2>Produits</h2>

<table>
<?php foreach($products as $e): ?>
<tr>
<td><?= htmlspecialchars($e['name']) ?></td>

<td>
<button class="edit"
onclick="openModal(<?= $e['id'] ?>,'<?= addslashes($e['name']) ?>','<?= addslashes($e['description']) ?>')">
✏️
</button>

<button class="delete"
onclick="removeProduct(<?= $e['id'] ?>)">
🗑️
</button>
</td>
</tr>
<?php endforeach; ?>
</table>

</div>

</div>

<div class="modal" id="modal">
<div class="modal-content">

<input type="hidden" id="edit_id">
<input id="edit_title">
<textarea id="edit_desc"></textarea>

<button class="btn" onclick="saveUpdate()">Enregistrer</button>
<button onclick="closeModal()">Annuler</button>

</div>
</div>

<script>

document.getElementById("form").addEventListener("submit",function(e){
e.preventDefault();

let fd = new FormData(this);
fd.append("action","create");

fetch("",{method:"POST",body:fd})
.then(r=>r.json())
.then(data=>{
alert(data.message);
if(data.status==="success") location.reload();
});
});

function openModal(id,title,desc){
document.getElementById("edit_id").value=id;
document.getElementById("edit_title").value=title;
document.getElementById("edit_desc").value=desc;
document.getElementById("modal").classList.add("show");
}

function closeModal(){
document.getElementById("modal").classList.remove("show");
}

function saveUpdate(){
let fd=new FormData();
fd.append("action","update_product");
fd.append("id",document.getElementById("edit_id").value);
fd.append("name",document.getElementById("edit_title").value);
fd.append("description",document.getElementById("edit_desc").value);

fetch("",{method:"POST",body:fd})
.then(r=>r.json())
.then(data=>{
alert(data.message);
location.reload();
});
}

function removeProduct(id){
if(!confirm("Supprimer ?")) return;

let fd=new FormData();
fd.append("action","delete_product");
fd.append("id",id);

fetch("",{method:"POST",body:fd})
.then(r=>r.json())
.then(data=>{
alert(data.message);
location.reload();
});
}

</script>

</body>
</html>

