create database Pesagem;

use Pesagem;

create table Residuos(
	codigo int not null primary key auto_increment,
    usuario varchar(120),
	dt datetime not null,
	categoria varchar(150) not null,
	peso decimal(10,2) not null,
	destino varchar(120) not null
)Engine = innoDB;

create table Categoria(
	codigo int not null primary key auto_increment,
    categoria varchar(150) not null
)Engine = innoDB;

drop table residuos;
drop table categoria;

select * from usuario;
select * from residuos;
select * from categoria;

INSERT INTO Categoria (categoria) VALUES 
    ('nao reciclavel'),
    ('reciclavel'),
    ('óleo'),
    ('tampinhas plasticas'),
    ('lacres de aluminio'),
    ('tecidos'),
    ('meias'),
    ('material de escrita'),
    ('esponjas'),
    ('eletrônicos'),
    ('pilhas e baterias'),
    ('infectante'),
    ('químicos'),
    ('lâmpada fluorescente'),
    ('tonners de impressora'),
    ('esmaltes'),
    ('cosméticos'),
    ('cartela de medicamento');


create table usuario(
	codigo int not null primary key auto_increment,
    usuario varchar(120) not null,
    senha varchar(120) not null,
    tipo varchar(50) not null
)engine = innoDB;
drop table usuario;
INSERT INTO `usuario` (`codigo`,`usuario`,`senha`,tipo) VALUES ('','adm','123','adm');


INSERT INTO `usuario` (`codigo`,`usuario`,`senha`) VALUES ('','Jhon','Jhon');
INSERT INTO `usuario` (`codigo`,`usuario`,`senha`) VALUES ('','Allan','Allan');
INSERT INTO Usuario (codigo, senha) VALUES ('admin', 'senha123');

INSERT INTO Residuos (codigo, usuario, dt, categoria, peso, destino) 
VALUES ('', 'João Silva', '2023-10-01 14:30:00', 'Plástico', 12.50, 'Centro de Reciclagem A'),
		('', 'Maria Oliveira', '2023-10-02 10:15:00', 'Papel', 8.75, 'Cooperativa de Reciclagem B'),
        ('', 'Carlos Souza', '2023-10-03 16:45:00', 'Vidro', 5.30, 'Usina de Reciclagem C'),
        ('', 'Ana Costa', '2023-10-04 09:00:00', 'Metal', 22.10, 'Depósito de Reciclagem D'),
        ('', 'Pedro Rocha', '2023-10-05 11:20:00', 'Orgânico', 15.00, 'Compostagem E');


alter table Residuos 
ADD COLUMN categoria_id INT,
ADD CONSTRAINT fk_categoria
FOREIGN KEY (codigo)
REFERENCES Categoria(codigo);