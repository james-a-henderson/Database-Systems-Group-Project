DROP TABLE PopMachine CASCADE CONSTRAINTS;
CREATE TABLE PopMachine
( 
  machineId         INT PRIMARY KEY,
  location      	VARCHAR(30) NOT NULL,
  dateInstalled     DATE NOT NULL,
  brand				VARCHAR(30) NOT NULL,
  maxQuantity		INT NOT NULL
);

DROP TABLE Pop CASCADE CONSTRAINTS;
CREATE TABLE Pop
(
  popId				INT PRIMARY KEY,
  name 				VARCHAR(30) NOT NULL,
  brand				VARCHAR(30) NOT NULL,
  cost 				FLOAT
);

DROP TABLE Payment CASCADE CONSTRAINTS;
CREATE TABLE Payment
(
  paymentId 		INT PRIMARY KEY,
  five				INT NOT NULL,
  one				INT NOT NULL,
  quarter			INT NOT NULL,
  dime 				INT NOT NULL,
  nickel			INT NOT NULL
);

DROP TABLE Inventory CASCADE CONSTRAINTS;
CREATE TABLE Inventory
(
  inventoryId		INT PRIMARY KEY,
  machineId			INT NOT NULL,
  popId				INT NOT NULL,
  quantity 			INT NOT NULL,
  FOREIGN KEY (machineId) REFERENCES PopMachine(machineId),
  FOREIGN KEY (popId) REFERENCES Pop(popId)
);

DROP TABLE Transaction CASCADE CONSTRAINTS;
CREATE TABLE Transaction
( 
  transactionId		INT PRIMARY KEY,
  inventoryId      	INT,
  popId     		INT,
  paymentId			INT,
  type				VARCHAR(30) NOT NULL,
  dateTime			DATE,
  total				FLOAT,
  FOREIGN KEY (inventoryId) REFERENCES Inventory(inventoryId),
  FOREIGN KEY (popId) REFERENCES Pop(popId),
  FOREIGN KEY (paymentId) REFERENCES Payment(paymentId)
);

DROP TABLE Balance CASCADE CONSTRAINTS;
CREATE TABLE Balance
(
  balanceId  		INT PRIMARY KEY,
  machineId			INT,
  five				INT NOT NULL,
  one				INT NOT NULL,
  quarter			INT NOT NULL,
  dime 				INT NOT NULL,
  nickel			INT NOT NULL,
  FOREIGN KEY (machineId) REFERENCES PopMachine(machineId)
);

-- AUTO INCREMENT SEQUENCES -- 
DROP SEQUENCE machine_sequence;
CREATE SEQUENCE machine_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

DROP SEQUENCE pop_sequence;
CREATE SEQUENCE pop_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

DROP SEQUENCE inv_sequence;
CREATE SEQUENCE inv_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

DROP SEQUENCE bal_sequence;
CREATE SEQUENCE bal_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

DROP SEQUENCE pay_sequence;
CREATE SEQUENCE pay_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

DROP SEQUENCE transact_sequence;
CREATE SEQUENCE transact_sequence START WITH 0
increment by 1
minvalue 0
maxvalue 10000;

-- END AUTO INCREMENTS -- 

-- SPROC sp_transact EXECUTED WHEN TRANSACTION TAKES PLACE. --
-- UPDATES TRANSACTION, PAYMENT, BALANCE, INVENTORY --
CREATE OR REPLACE PROCEDURE sp_transact(
	machId IN NUMBER, inventId IN NUMBER, trans_type IN VARCHAR, five IN NUMBER, one IN NUMBER, 
	quarter IN NUMBER, dime IN NUMBER, nickel IN NUMBER, total IN NUMBER) AS

	changeBack NUMBER;
	payId NUMBER;
	transId NUMBER;
	popId NUMBER;
	quantityChk NUMBER;

	remQuarter NUMBER;
	remDime NUMBER;
	remNickel NUMBER;

	BEGIN
		SELECT Inventory.popId INTO popId FROM Inventory WHERE inventoryId = inventId;
		IF trans_type = 'cash' THEN
			SELECT pay_sequence.nextval INTO payId FROM DUAL;
			
			INSERT INTO Payment VALUES (payId, five, one, quarter, dime, nickel);
			UPDATE Inventory SET quantity = (quantity - 1) WHERE inventoryId = inventId;
			
			changeBack := total - ((5 * five) + (1 * one) + (.25 * quarter) + (.1 * dime) + (.05 * nickel));
			
			UPDATE Balance 
				SET Balance.five = (Balance.five + sp_transact.five), 
				    Balance.one = (Balance.one + sp_transact.one), 
				    Balance.quarter = (Balance.quarter + sp_transact.quarter), 
				    Balance.dime = (Balance.dime + sp_transact.dime), 
				    Balance.nickel = (Balance.nickel + sp_transact.nickel)
			WHERE Balance.machineId = machId;

			SELECT Balance.quarter INTO remQuarter FROM Balance;
			SELECT Balance.dime INTO remDime FROM Balance;
			SELECT Balance.nickel INTO remNickel FROM Balance;

			IF remQuarter > 2 THEN
				WHILE changeBack <= -.25
				LOOP
					changeBack := changeBack + .25;
					UPDATE Balance SET Balance.quarter = (Balance.quarter - 1) WHERE Balance.machineId = machId;
				END LOOP;
			END IF;
			IF remDime > 2 THEN
				WHILE changeBack <= -.10
				LOOP
					changeBack := changeBack + .10;
					UPDATE Balance SET Balance.dime = (Balance.dime - 1) WHERE Balance.machineId = machId;
				END LOOP;
			END IF;
			IF remNickel < 1 THEN
				WHILE changeBack <= -.05
				LOOP
					changeBack := changeBack + .05;
					UPDATE Balance SET Balance.nickel = (Balance.nickel - 1) WHERE Balance.machineId = machId;
				END LOOP;
			END IF;
			SELECT transact_sequence.nextval INTO transId FROM DUAL;
			INSERT INTO Transaction VALUES (transId, inventId, popId, payId, 'cash', CURRENT_TIMESTAMP(3), total);
		END IF;
		IF trans_type = 'credit' THEN
			SELECT transact_sequence.nextval INTO transId FROM DUAL;
			UPDATE Inventory Set quantity = (quantity - 1) WHERE inventoryId = inventId;
			INSERT INTO Transaction VALUES (transId, inventId, popId, NULL, 'credit', CURRENT_TIMESTAMP(3), total);	
		END IF;	
	END;