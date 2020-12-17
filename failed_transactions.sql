SELECT merchantid, `status`, chargemessage, createddate, COUNT(id) AS numtransactions 
FROM flutterwave_assessments.transaction_table 
WHERE createddate >= '2020-10-01' 
AND `status` = 'failed' 
GROUP BY merchantid, status, chargemessage, createddate;
