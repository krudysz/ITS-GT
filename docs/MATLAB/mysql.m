close all force,clear all,clear classes,clc
%----------------------------------------------------%
% Database
host     = 'localhost';
user     = 'root';
password = 'csip';
dbName   = 'its';

% JDBC Parameters
jdbcString = sprintf('jdbc:mysql://%s/%s', host, dbName);
jdbcDriver = 'com.mysql.jdbc.Driver';

% Set this to the path to your MySQL Connector/J JAR
%javaaddpath('/home/gte269x/mysql-connector-java-5.1.18/mysql-connector-java-5.1.18-bin.jar')

% Create the database connection object
dbConn = database(dbName, user , password, jdbcDriver, jdbcString);

% Check to make sure that we successfully connected
if isconnection(dbConn) 
    sql  = exec(dbConn,'SELECT id,name from tags LIMIT 10');
    sql  = fetch(e);
    data = sql.Data    
    % --- OPTION 2 ----%
    %result = get(fetch(exec(dbConn, 'SELECT 1')), 'Data');
    %disp(result);
else
    disp(sprintf('Connection failed: %s', dbConn.Message));
end

% Close the connection so we don't run out of MySQL threads
close(dbConn);
